<?php

namespace App\Console\Commands;

use App\Models\Group\AgeGroup;
use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeContingentRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrations:merge
                            {contingent_id : ID Kontingen yang akan di-merge registrasinya}
                            {--keep=oldest : Registrasi mana yang dipertahankan: oldest (ID terkecil) atau newest (ID terbesar)}
                            {--dry-run : Tampilkan preview tanpa mengubah data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gabungkan semua registrasi dari satu kontingen menjadi satu registrasi tunggal';

    public function handle(): int
    {
        $contingentId = (int) $this->argument('contingent_id');
        $keepStrategy = $this->option('keep');
        $isDryRun = $this->option('dry-run');

        // ── 1. Ambil semua registrasi kontingen
        $registrations = Registration::where('contingent_id', $contingentId)
            ->with(['athletes', 'officials'])
            ->orderBy('id')
            ->get();

        if ($registrations->count() < 2) {
            $this->warn("Kontingen ID {$contingentId} hanya memiliki {$registrations->count()} registrasi. Tidak perlu di-merge.");

            return self::SUCCESS;
        }

        $this->info("=== MERGE REGISTRASI KONTINGEN ID: {$contingentId} ===");
        $this->line("Total registrasi ditemukan: {$registrations->count()}");

        // ── 2. Tentukan registrasi yang dipertahankan (master)
        $masterReg = $keepStrategy === 'newest'
            ? $registrations->sortByDesc('id')->first()
            : $registrations->sortBy('id')->first();

        $toMerge = $registrations->where('id', '!=', $masterReg->id)->values();

        $this->table(
            ['#', 'ID', 'Referral Code', 'Status', 'Atlet', 'Official', 'Final Amount'],
            $registrations->map(fn ($r, $i) => [
                $i + 1,
                $r->id.($r->id === $masterReg->id ? ' [MASTER]' : ' [HAPUS]'),
                $r->referral_code,
                strtoupper($r->status),
                $r->athletes->count(),
                $r->officials->count(),
                'Rp '.number_format($r->final_amount, 0, ',', '.'),
            ])
        );

        if ($isDryRun) {
            $this->warn('[DRY RUN] Tidak ada perubahan yang dibuat. Hapus --dry-run untuk menjalankan merge.');

            return self::SUCCESS;
        }

        if (! $this->confirm("Lanjutkan merge {$toMerge->count()} registrasi ke ID {$masterReg->id} ({$masterReg->referral_code})?")) {
            $this->line('Dibatalkan.');

            return self::SUCCESS;
        }

        // ── 3. Jalankan merge dalam transaksi
        DB::transaction(function () use ($masterReg, $toMerge) {

            foreach ($toMerge as $reg) {
                $this->line("  → Memproses registrasi ID:{$reg->id} ({$reg->referral_code})...");

                // 3a. Pindahkan atlet ke master
                foreach ($reg->athletes as $athlete) {
                    // Cek apakah atlet sudah ada di master registration (cegah duplikat)
                    $alreadyAttached = $masterReg->athletes()
                        ->where('athlete_id', $athlete->id)
                        ->exists();

                    if (! $alreadyAttached) {
                        $pivotData = [
                            'weight' => $athlete->pivot->weight,
                            'kyu' => $athlete->pivot->kyu,
                            'rank' => $athlete->pivot->rank,
                            'age_group' => $athlete->pivot->age_group,
                            'match_type' => $athlete->pivot->match_type,
                            'dojo_origin' => $athlete->pivot->dojo_origin,
                            'city' => $athlete->pivot->city,
                        ];
                        $masterReg->athletes()->attach($athlete->id, $pivotData);
                        $this->line("    ✔ Atlet: {$athlete->name}");
                    } else {
                        $this->warn("    ⚠ Atlet: {$athlete->name} sudah ada di master, dilewati.");
                    }

                    // 3b. Pindahkan entri athlete_match_number ke master registration
                    DB::table('athlete_match_number')
                        ->where('registration_id', $reg->id)
                        ->where('athlete_id', $athlete->id)
                        ->update(['registration_id' => $masterReg->id]);
                }

                // 3c. Pindahkan official ke master (cegah duplikat)
                foreach ($reg->officials as $official) {
                    $alreadyAttached = $masterReg->officials()
                        ->where('official_id', $official->id)
                        ->exists();

                    if (! $alreadyAttached) {
                        $masterReg->officials()->attach($official->id, [
                            'role' => $official->pivot->role,
                        ]);
                        $this->line("    ✔ Official: {$official->name}");
                    } else {
                        $this->warn("    ⚠ Official: {$official->name} sudah ada di master, dilewati.");
                    }
                }

                // 3d. Hapus registrasi yang sudah dipindahkan
                $reg->athletes()->detach();
                $reg->officials()->detach();
                $reg->delete();

                $this->line("    ✔ Registrasi ID:{$reg->id} dihapus.");
            }

            // ── 4. Recalculate biaya master registration
            $this->recalculateFees($masterReg);
            $this->line('  ✔ Biaya registrasi master direcalculate.');
        });

        // Refresh dan tampilkan hasil
        $masterReg->refresh()->load(['athletes', 'officials']);

        $this->newLine();
        $this->info('✅ MERGE BERHASIL!');
        $this->table(
            ['Field', 'Nilai'],
            [
                ['Registration ID', $masterReg->id],
                ['Referral Code', $masterReg->referral_code],
                ['Status', strtoupper($masterReg->status)],
                ['Total Atlet', $masterReg->athletes->count()],
                ['Total Official', $masterReg->officials->count()],
                ['Total Biaya', 'Rp '.number_format($masterReg->total_cost, 0, ',', '.')],
                ['Final Amount', 'Rp '.number_format($masterReg->final_amount, 0, ',', '.')],
            ]
        );

        return self::SUCCESS;
    }

    private function recalculateFees(Registration $registration): void
    {
        $contingentFee = 2500000;
        $ageGroups = AgeGroup::pluck('price', 'name')->toArray();

        $athleteFeeSum = 0;
        foreach ($registration->athletes()->get() as $athlete) {
            $groupName = $athlete->pivot->age_group;
            $athleteFeeSum += $ageGroups[$groupName] ?? 0;
        }

        $totalCost = $contingentFee + $athleteFeeSum;
        $finalAmount = $totalCost + (int) $registration->unique_code;

        $registration->update([
            'total_cost' => $totalCost,
            'final_amount' => $finalAmount,
        ]);
    }
}
