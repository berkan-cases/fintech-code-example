<?php

declare(strict_types=1);

namespace App\Facades;

use App\Enums\PaymentStatus;
use App\Exceptions\AlreadyRepaidException;
use App\Exceptions\AmountHigherThanOutstandingAmountException;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanFacade
{
    public static function createLoan(User $customer, $amount, $currencyCode, $terms, $processedAt)
    {
        try {
            DB::beginTransaction();

            $loan = $customer->loan()->create([
                'amount' => $amount,
                'terms' => $terms,
                'outstanding_amount' => $amount,
                'currency_code' => $currencyCode,
                'processed_at' => Carbon::parse($processedAt)->format('Y-m-d'),
                'status' => PaymentStatus::DUE,
            ]);

            $installmentAmount = self::calculateTermAmount($amount, $terms);

            $fractionalSum = self::calculateFractionalSum($amount, $terms);

            $schedules = [];

            for ($i = 1; $i <= $terms; $i++) {
                // if last instalment should be added fraction value.
                $amount = $terms == $i ? ($installmentAmount + $fractionalSum) : $installmentAmount;

                $schedules[] = [
                    'amount' => $amount,
                    'outstanding_amount' => $amount,
                    'currency_code' => $currencyCode,
                    'due_date' => Carbon::parse($processedAt)->addMonth($i)->format('Y-m-d'),
                    'status' => PaymentStatus::DUE,
                ];
            }

            $loan->scheduledRepayment()->createMany($schedules);

            DB::commit();

            return $loan;
        } catch (\Exception $exception) {
            DB::rollBack();

            report($exception);
        }

        return [];
    }

    public static function repayLoan($loan, $receivedRepayment, $currencyCode, Carbon $receivedAt): Loan
    {
        throw_if($loan->status == PaymentStatus::REPAID, new AlreadyRepaidException());
        throw_if($receivedRepayment > $loan->amount, new AmountHigherThanOutstandingAmountException());

        $outstandingAmount = $loan->outstanding_amount - $receivedRepayment;

        $loan->update([
            'outstanding_amount' => $outstandingAmount,
            'status'             => $outstandingAmount == 0 ? PaymentStatus::REPAID->value : PaymentStatus::DUE->value,
        ]);

        $scheduledRepayments = $loan->scheduledRepayments()->where('status', PaymentStatus::DUE->value)->orderBy('due_date')->get();

        // i am cloning first value because using for received payment
        $receivedRepaymentClone = $receivedRepayment;

        foreach ($scheduledRepayments as $repayment) {
            $outstandingAmount = ($repayment->outstanding_amount - $receivedRepayment) * -1;
            $receivedRepayment = $outstandingAmount;

            // if you have to remaining value i'm set value zero for remaining
            if ($outstandingAmount > 0) {
                $outstandingAmount = 0;
            }

            $status = PaymentStatus::REPAID->value;

            // i'm using abs function because we need to always positive value.
            if (abs($outstandingAmount) > 0) {
                $status = PaymentStatus::PARTIAL->value;
            }

            $repayment->update([
                'outstanding_amount' => abs($outstandingAmount),
                'status'             => $status,
            ]);

            if ($repayment->outstanding_amount != $repayment->amount && $receivedRepayment <= 0) {
                break;
            }
        }

        $loan->receivedRepayments()->create([
            'amount'        => $receivedRepaymentClone,
            'currency_code' => $currencyCode,
            'received_at'   => Carbon::parse($receivedAt)->format('Y-m-d'),
        ]);

        return $loan;
    }

    private static function calculateTermAmount($amount, $term): float|int
    {
        return (int) ($amount / $term);
    }

    private static function calculateFractionalSum($amount, $term): float|int
    {
        return (($amount / $term) - self::calculateTermAmount($amount, $term)) * $term;
    }
}
