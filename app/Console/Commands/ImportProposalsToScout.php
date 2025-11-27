<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Proposal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportProposalsToScout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:import-proposals
                            {--chunk=500 : Number of proposals to import per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all existing proposals into the search index (Algolia)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $driver = config('scout.driver');

        if ($driver !== 'algolia') {
            $this->error('Scout driver is set to "'.$driver.'". Please configure Algolia first.');
            $this->info('Set SCOUT_DRIVER=algolia in your .env file and configure ALGOLIA_APP_ID and ALGOLIA_SECRET.');

            return Command::FAILURE;
        }

        if (empty(config('scout.algolia.id')) || empty(config('scout.algolia.secret'))) {
            $this->error('Algolia credentials are not configured.');
            $this->info('Please set ALGOLIA_APP_ID and ALGOLIA_SECRET in your .env file.');

            return Command::FAILURE;
        }

        $this->info('Starting proposal import to Algolia...');

        $chunkSize = (int) $this->option('chunk');
        $total = Proposal::count();
        $imported = 0;

        $this->info("Found {$total} proposals to import.");

        Proposal::with(['user', 'tags'])
            ->chunk($chunkSize, function ($proposals) use (&$imported, $total) {
                foreach ($proposals as $proposal) {
                    try {
                        $proposal->searchable();
                        $imported++;

                        if ($imported % 50 === 0) {
                            $this->info("Imported {$imported}/{$total} proposals...");
                        }
                    } catch (\Exception $e) {
                        $this->warn("Failed to import proposal ID {$proposal->id}: {$e->getMessage()}");
                        Log::error('Failed to import proposal to Scout', [
                            'proposal_id' => $proposal->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Successfully imported {$imported}/{$total} proposals to the search index.");

        return Command::SUCCESS;
    }
}
