<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:cleanup {--days=90}';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'পুরনো audit logs মুছে ফেলুন';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days    = $this->option('days');
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))
                           ->delete();

        $this->info("{$deleted}টি পুরনো log মুছে ফেলা হয়েছে।");
    }
}
