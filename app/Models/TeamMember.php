<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role', 'focus', 'bio', 'highlights', 'photo',
        'is_specialist', 'display_order', 'published',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'is_specialist' => 'boolean',
            'published' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /** @param  Builder<TeamMember>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    /** @param  Builder<TeamMember>  $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order')->orderBy('id');
    }

    /**
     * The role seats (Sales / Front / Tech) only.
     *
     * The technical specialist is a separate About page block, so the
     * homepage rail counts people by job rather than by headcount.
     *
     * @param  Builder<TeamMember>  $query
     */
    public function scopeNotSpecialist(Builder $query): void
    {
        $query->where('is_specialist', false);
    }

    public function initials(): string
    {
        if (! $this->name) {
            return str($this->role)->substr(0, 2)->upper()->toString();
        }

        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => str($part)->substr(0, 1)->upper())
            ->implode('');
    }
}
