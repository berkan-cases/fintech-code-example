<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ReceivedRepayment
 *
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|ReceivedRepayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceivedRepayment newQuery()
 * @method static \Illuminate\Database\Query\Builder|ReceivedRepayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceivedRepayment query()
 * @method static \Illuminate\Database\Query\Builder|ReceivedRepayment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ReceivedRepayment withoutTrashed()
 * @mixin \Eloquent
 */
class ReceivedRepayment extends Model
{
    use HasFactory;

    use SoftDeletes;

    // region Attributes

    protected $fillable = [
        'loan_id',
        'amount',
        'currency_code',
        'received_at',
    ];

    protected $casts = [
        'loan_id'                     => 'integer',
        'amount'                      => 'integer',
        'currency_code'               => 'string',
        'received_at'                 => 'datetime',
    ];

    // endregion

    // region Relations

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // endregion
}
