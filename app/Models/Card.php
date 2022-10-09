<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Card
 *
 * @mixin IdeHelperCard
 * @property-read bool $is_active
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CardTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User|null $user
 * @method static Builder|Card active()
 * @method static \Database\Factories\CardFactory factory(...$parameters)
 * @method static Builder|Card newModelQuery()
 * @method static Builder|Card newQuery()
 * @method static \Illuminate\Database\Query\Builder|Card onlyTrashed()
 * @method static Builder|Card query()
 * @method static \Illuminate\Database\Query\Builder|Card withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Card withoutTrashed()
 */
class Card extends Model
{
    use HasFactory;

    use SoftDeletes;

    // region Attributes

    protected $fillable = [
        'user_id',
        'number',
        'type',
        'expiration_date',
        'disabled_at',
    ];

    protected $casts = [
        'user_id'         => 'integer',
        'number'          => 'integer',
        'type'            => 'string',
        'expiration_date' => 'datetime',
        'disabled_at'     => 'datetime',
    ];

    // endregion

    // region Accessors

    /**
     * Convert disabled_at in boolean attribute.
     */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->disabled_at);
    }

    // endregion

    // region Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CardTransaction::class);
    }

    // endregion

    // region Scopes

    /**
     * Scope active Cards.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('disabled_at');
    }

    // endregion
}
