<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CurrencyType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Loan
 *
 * @property CurrencyType $currency_code
 * @property PaymentStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReceivedRepayment[] $receivedRepayments
 * @property-read int|null $received_repayments_count
 * @property-read \App\Models\ScheduledRepayment|null $scheduledRepayment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ScheduledRepayment[] $scheduledRepayments
 * @property-read int|null $scheduled_repayments_count
 * @method static \Database\Factories\LoanFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Query\Builder|Loan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Query\Builder|Loan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Loan withoutTrashed()
 * @mixin \Eloquent
 */
class Loan extends Model
{
    use HasFactory;

    use SoftDeletes;

    // region Attributes

    protected $fillable = [
        'user_id',
        'amount',
        'terms',
        'outstanding_amount',
        'currency_code',
        'processed_at',
        'status',
    ];

    protected $casts = [
        'user_id'                     => 'integer',
        'amount'                      => 'integer',
        'terms'                       => 'integer',
        'outstanding_amount'          => 'integer',
        'currency_code'               => CurrencyType::class,
        'processed_at'                => 'datetime',
        'status'                      => PaymentStatus::class,
    ];

    // endregion

    // region Relations

    public function scheduledRepayment(): HasOne
    {
        return $this->hasOne(ScheduledRepayment::class);
    }

    public function scheduledRepayments(): HasMany
    {
        return $this->hasMany(ScheduledRepayment::class);
    }

    public function receivedRepayments(): HasMany
    {
        return $this->hasMany(ReceivedRepayment::class);
    }

    // endregion
}
