<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateContingent16May extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-duplicate-contingent16-may {--force : Force the operation to run without prompting} {--dry-run : Print the changes that would be made without actually making them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate Contingents created on 16 May 2026 and their associated Athletes and Registrations.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        // 1. Get Contingents created on 16 May 2026
        $contingentsQuery = Contingent::whereDate('created_at', '2026-05-16');
        $contingentCount = $contingentsQuery->count();
        $contingents = $contingentsQuery->get();

        if ($contingentCount === 0) {
            $this->info('No contingents found created on 16 May 2026.');

            return 0;
        }

        $contingentIds = $contingents->pluck('id')->toArray();

        // 2. Get associated Registrations
        $registrationsQuery = Registration::whereIn('contingent_id', $contingentIds);
        $registrationCount = $registrationsQuery->count();
        $registrations = $registrationsQuery->get();
        $registrationIds = $registrations->pluck('id')->toArray();

        // 3. Get associated Athletes (athletes belonging to these contingents)
        $athleteIds = DB::table('athlete_contingent')
            ->whereIn('contingent_id', $contingentIds)
            ->pluck('athlete_id')
            ->toArray();

        $athletesQuery = Athlete::whereIn('id', $athleteIds);
        $athleteCount = $athletesQuery->count();
        $athletes = $athletesQuery->get();

        $this->info('=== Summary of Data to Delete ===');
        $this->line("- Contingents (Created on 16 May 2026): {$contingentCount}");
        foreach ($contingents as $contingent) {
            $this->line("  * ID: {$contingent->id} - {$contingent->name} (Created: {$contingent->created_at})");
        }

        $this->line("- Registrations: {$registrationCount}");
        foreach ($registrations as $reg) {
            $this->line("  * ID: {$reg->id} (Contingent ID: {$reg->contingent_id}, Status: {$reg->status})");
        }

        $this->line("- Athletes (associated with the above contingents): {$athleteCount}");
        if ($athleteCount > 0) {
            $this->line('  (These athletes will be permanently deleted, along with their association records)');
        }

        if ($dryRun) {
            $this->warn('*** DRY RUN MODE: No database changes were made. ***');

            return 0;
        }

        if (! $force && ! $this->confirm('Are you sure you want to delete these records? This action CANNOT be undone!', false)) {
            $this->error('Operation cancelled.');

            return 1;
        }

        $this->info('Deleting records...');

        try {
            DB::transaction(function () use ($contingentIds, $registrationIds, $athleteIds) {
                // Delete Registrations
                if (! empty($registrationIds)) {
                    Registration::whereIn('id', $registrationIds)->delete();
                }

                // Delete Athletes
                if (! empty($athleteIds)) {
                    Athlete::whereIn('id', $athleteIds)->delete();
                }

                // Delete Contingents
                if (! empty($contingentIds)) {
                    Contingent::whereIn('id', $contingentIds)->delete();
                }
            });

            $this->info('Successfully deleted all duplicate records!');

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred during deletion: '.$e->getMessage());

            return 1;
        }
    }
}
