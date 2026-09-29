<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceTier extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'name', 'slug', 'model', 'includes', 'summary', 'short_description',
        'description', 'audience', 'problems_solved', 'implementation',
        'example_project', 'cta_label', 'cta_url', 'badge', 'highlights',
        'display_order', 'is_active', 'pricing_published', 'price_label',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'display_order' => 'integer',
            'is_active' => 'boolean',
            'pricing_published' => 'boolean',
        ];
    }

    /** @return HasMany<ServiceFeature, $this> */
    public function features(): HasMany
    {
        return $this->hasMany(ServiceFeature::class)->orderBy('display_order')->orderBy('id');
    }

    /** Only confirmed items reach the public site (plan.md acceptance #14). */
    public function publishedFeatures(): HasMany
    {
        return $this->features()->where('status', self::STATUS_CONFIRMED);
    }

    /** @param  Builder<ServiceTier>  $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param  Builder<ServiceTier>  $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order')->orderBy('id');
    }

    /** The numeral used in "Recursive N", handy for compact displays. */
    public function level(): string
    {
        return str($this->name)->after('Recursive ')->trim()->toString();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
