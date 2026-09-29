<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_QUALIFIED = 'qualified';

    public const STATUS_PROPOSAL = 'proposal';

    public const STATUS_WON = 'won';

    public const STATUS_LOST = 'lost';

    public const TIER_NOT_SURE = 'not_sure';

    protected $fillable = [
        'first_name', 'last_name', 'clinic_name', 'email', 'phone', 'city', 'website',
        'preferred_contact', 'challenge', 'booking_process', 'inquiry_process',
        'software_usage', 'tier_interest', 'tier_label', 'message', 'consent',
        'consented_at', 'source_url', 'utm_source', 'utm_medium', 'utm_campaign',
        'utm_term', 'utm_content', 'status', 'assigned_to', 'ip_address', 'user_agent',
    ];

    protected $hidden = ['ip_address', 'user_agent'];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<LeadNote, $this> */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    /** @param  Builder<Lead>  $query */
    public function scopeStatus(Builder $query, ?string $status): void
    {
        if ($status && array_key_exists($status, self::statuses())) {
            $query->where('status', $status);
        }
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_QUALIFIED => 'Qualified',
            self::STATUS_PROPOSAL => 'Proposal',
            self::STATUS_WON => 'Won',
            self::STATUS_LOST => 'Lost',
        ];
    }

    public static function statusName(?string $status): string
    {
        return self::statuses()[$status] ?? 'New';
    }

    public function statusLabel(): string
    {
        return self::statusName($this->status);
    }

    public function tierLabel(): string
    {
        return $this->tier_label ?: match ($this->tier_interest) {
            self::TIER_NOT_SURE => 'Not sure yet',
            default => str($this->tier_interest)->headline()->toString(),
        };
    }

    /** Used for admin list badges. */
    public function statusTone(): string
    {
        return match ($this->status) {
            self::STATUS_NEW => 'lime',
            self::STATUS_CONTACTED => 'sky',
            self::STATUS_QUALIFIED => 'amber',
            self::STATUS_PROPOSAL => 'violet',
            self::STATUS_WON => 'emerald',
            default => 'zinc',
        };
    }
}
