<?php

namespace App\Jobs\Central;

use App\Events\Central\SyncPaymentStripeEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($progress = 0; $progress <= 100; $progress += 10) {
            // Simula trabalho
            sleep(1);

            // Dispara o evento com o progresso atual
            event(new SyncPaymentStripeEvent($progress));

            // Log para debug
            Log::info("Progress: {$progress}%");
        }
    }
}
