<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'year', 'category', 'client', 'summary', 'overview',
        'business_problem', 'solution', 'technologies', 'integrations', 'results',
        'external_url', 'thumbnail', 'accent', 'featured', 'published', 'display_order',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'integrations' => 'array',
            'featured' => 'boolean',
            'published' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /** @return HasMany<ProjectFeature, $this> */
    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class)->orderBy('display_order')->orderBy('id');
    }

    /** @param  Builder<Project>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    /** @param  Builder<Project>  $query */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /** @param  Builder<Project>  $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order')->orderBy('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
