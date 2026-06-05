<?php

namespace App\Console\Commands;

use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:remove-empty-contingents {--force : Force the operation to run without prompting} {--dry-run : Print the changes that would be made without actually making them}')]
#[Description('Remove contingents that have 0 athletes and 0 officials.')]
class RemoveEmptyContingents extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        // Fetch contingents with 0 athletes and 0 officials
        $contingents = Contingent::addSelect([
            'athletes_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_athlete', 'registrations.id', '=', 'registration_athlete.registration_id')
                ->selectRaw('count(*)'),
            'officials_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_official', 'registrations.id', '=', 'registration_official.registration_id')
                ->selectRaw('count(*)'),
        ])->get()->filter(function ($ctg) {
            return $ctg->athletes_count == 0 && $ctg->officials_count == 0;
        });

        if ($contingents->isEmpty()) {
            $this->info('No empty contingents (0 athletes and 0 officials) found.');

            return 0;
        }

        $this->info('=== Summary of Empty Contingents to Delete ===');
        $this->line("- Total Empty Contingents: {$contingents->count()}");
        foreach ($contingents as $contingent) {
            $this->line("  * ID: {$contingent->id} - {$contingent->name} (Created: {$contingent->created_at})");
        }

        if ($dryRun) {
            $this->warn('*** DRY RUN MODE: No database changes were made. ***');

            return 0;
        }

        if (! $force && ! $this->confirm('Are you sure you want to delete these empty contingents? This action CANNOT be undone!', false)) {
            $this->error('Operation cancelled.');

            return 1;
        }

        $this->info('Deleting empty contingents...');

        try {
            DB::transaction(function () use ($contingents) {
                $contingentIds = $contingents->pluck('id')->toArray();
                Contingent::whereIn('id', $contingentIds)->delete();
            });

            $this->info('Successfully deleted all empty contingents!');

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred during deletion: '.$e->getMessage());

            return 1;
        }
    }
}
