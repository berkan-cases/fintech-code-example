<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\PaymentStatus;
use App\Models\Loan;

class LoanObServer
{
    /**
     * Handle the Loan "updated" event.
     *
     * @return void
     */
    public function updated(Loan $loan)
    {
        if ($loan->isDirty('outstanding_amount') && $loan->outstanding_amount == 0) {
            //logger()->info('outstanding_amount updated and outstanding_amount is zero.');
            //$loan->status = PaymentStatus::REPAID->value;
            //$loan->save();
        }
    }
}
