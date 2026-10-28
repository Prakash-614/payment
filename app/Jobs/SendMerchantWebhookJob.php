<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\WebhookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMerchantWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public Transaction $transaction,
        public string $event = 'payment.succeeded'
    ) {
    }

    public function handle(): void
    {
        WebhookService::dispatch($this->transaction, $this->event);
    }
}