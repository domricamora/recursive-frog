<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_tier_id', 'name', 'description', 'status', 'group', 'display_order',
    ];

    protected function casts(): array
    {
        return ['display_order' => 'integer'];
    }

    /** @return BelongsTo<ServiceTier, $this> */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(ServiceTier::class, 'service_tier_id');
    }

    public function isPublished(): bool
    {
        return $this->status === ServiceTier::STATUS_CONFIRMED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            ServiceTier::STATUS_CONFIRMED => 'Published',
            ServiceTier::STATUS_ARCHIVED => 'Archived',
            default => 'Draft',
        };
    }
}
