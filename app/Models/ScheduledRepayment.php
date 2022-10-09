<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ScheduledRepayment
 *
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledRepayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledRepayment newQuery()
 * @method static \Illuminate\Database\Query\Builder|ScheduledRepayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledRepayment query()
 * @method static \Illuminate\Database\Query\Builder|ScheduledRepayment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ScheduledRepayment withoutTrashed()
 * @mixin \Eloquent
 */
class ScheduledRepayment extends Model
{
    use HasFactory;

    use SoftDeletes;

    // region Attributes

    protected $fillable = [
        'loan_id',
        'amount',
        'outstanding_amount',
        'currency_code',
        'due_date',
        'status',
    ];

    protected $casts = [
        'loan_id'                     => 'integer',
        'amount'                      => 'integer',
        'outstanding_amount'          => 'integer',
        'currency_code'               => 'string',
        'due_date'                    => 'datetime',
        'status'                      => 'string',
    ];

    // endregion

    // region Relations

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // endregion
}
