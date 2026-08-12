<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\IdempotencyService;

class CleanupIdempotencyKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idempotency:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired idempotency keys মুছে ফেলুন';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        IdempotencyService::cleanup();
        $this->info('Expired idempotency keys মুছে ফেলা হয়েছে।');
    }
}
