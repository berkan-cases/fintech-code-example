<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CurrencyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CardTransaction
 *
 * @mixin IdeHelperCardTransaction
 * @property CurrencyType $currency_code
 * @property-read \App\Models\Card|null $card
 * @method static \Database\Factories\CardTransactionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CardTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardTransaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|CardTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CardTransaction query()
 * @method static \Illuminate\Database\Query\Builder|CardTransaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CardTransaction withoutTrashed()
 */
class CardTransaction extends Model
{
    use HasFactory;

    use SoftDeletes;

    // region Attributes

    protected $fillable = [
        'card_id',
        'amount',
        'currency_code',
    ];

    protected $casts = [
        'card_id' => 'integer',
        'amount'         => 'integer',
        'currency_code'  => CurrencyType::class,
    ];

    // endregion

    // region Relations

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    // endregion
}
