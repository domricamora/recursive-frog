<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'category', 'display_order', 'published'];

    protected function casts(): array
    {
        return ['published' => 'boolean', 'display_order' => 'integer'];
    }

    /** @param  Builder<Faq>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    /** @param  Builder<Faq>  $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order')->orderBy('id');
    }
}
