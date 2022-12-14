<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessCardTransactionJob implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    protected int $cardTransactionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $cardTransaction)
    {
        $this->cardTransactionId = $cardTransaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Http::post('http://you-should-mock-this-job', [
            'card_id_transaction_id' => $this->cardTransactionId,
        ]);
    }
}
