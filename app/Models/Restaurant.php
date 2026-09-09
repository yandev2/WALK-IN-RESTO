<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use App\Models\Concerns\PurgesPublicDiskFiles;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Restaurant extends Model implements HasAvatar, HasName
{
    use PurgesPublicDiskFiles;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'timezone' => 'Asia/Jakarta',
        'currency' => 'IDR',
        'is_active' => true,
        'listed_in_directory' => true,
        'landing_enabled' => true,
        'is_recommended' => false,
        'plan_code' => 'management_kds',
        'subscription_status' => 'active',
    ];

    protected $fillable = [
        'public_id',
        'name',
        'slug',
        'custom_domain',
        'legal_name',
        'npwp',
        'logo_path',
        'timezone',
        'currency',
        'is_active',
        'listed_in_directory',
        'landing_enabled',
        'is_recommended',
        'price_level',
        'facilities',
        'plan_code',
        'subscription_status',
        'trial_ends_at',
        'grace_ends_at',
        'subscribed_until',
        'commission_percentage',
        'fonnte_api_key_encrypted',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'listed_in_directory' => 'boolean',
            'landing_enabled' => 'boolean',
            'is_recommended' => 'boolean',
            'subscription_status' => SubscriptionStatus::class,
            'trial_ends_at' => 'datetime',
            'grace_ends_at' => 'datetime',
            'subscribed_until' => 'datetime',
            'commission_percentage' => 'float',
            'facilities' => 'array',
            'settings' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $restaurant): void {
            if (blank($restaurant->public_id)) {
                $restaurant->public_id = (string) Str::ulid();
            }
        });
    }

    public function hasFonnteKey(): bool
    {
        return filled($this->fonnte_api_key_encrypted);
    }

    public function fonnteApiKey(): ?string
    {
        if (! $this->hasFonnteKey()) {
            return null;
        }

        try {
            return Crypt::decryptString($this->fonnte_api_key_encrypted);
        } catch (\Throwable) {
            return null;
        }
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return filled($this->logo_path)
            ? Storage::disk('public')->url($this->logo_path)
            : null;
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_code', 'code');
    }

    public function subscriptionInvoices(): HasMany
    {
        return $this->hasMany(SubscriptionInvoice::class);
    }

    public function isListedInDirectory(): bool
    {
        return $this->is_active && ($this->listed_in_directory ?? true);
    }

    public function isLandingPublic(): bool
    {
        return $this->is_active && ($this->landing_enabled ?? true);
    }

    public function isRecommended(): bool
    {
        return $this->isListedInDirectory() && (bool) ($this->is_recommended ?? false);
    }

    public function scopeListedInDirectory(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('listed_in_directory', true);
    }

    public function scopeRecommended(Builder $query): Builder
    {
        return $query
            ->listedInDirectory()
            ->where('is_recommended', true);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class);
    }

    public function defaultOutlet(): HasOne
    {
        return $this->hasOne(Outlet::class)->where('is_default', true);
    }

    public function cmsProfile(): HasOne
    {
        return $this->hasOne(CmsProfile::class);
    }

    public function cmsFaqs(): HasMany
    {
        return $this->hasMany(CmsFaq::class);
    }

    public function cmsBanners(): HasMany
    {
        return $this->hasMany(CmsBanner::class);
    }

    public function cmsGalleryImages(): HasMany
    {
        return $this->hasMany(CmsGalleryImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(RestaurantReview::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(RestaurantCategory::class, 'restaurant_restaurant_category')
            ->orderBy('restaurant_categories.sort_order')
            ->orderBy('restaurant_categories.name');
    }

    public function displayCategoriesLabel(): string
    {
        $names = $this->relationLoaded('categories')
            ? $this->categories->pluck('name')
            : $this->categories()->pluck('name');

        return $names->filter()->implode(' • ');
    }

    public function priceLevelLabel(): ?string
    {
        if (! filled($this->price_level)) {
            return null;
        }

        return str_repeat('$', max(1, min(4, (int) $this->price_level)));
    }

    public function hasFacility(string $key): bool
    {
        $facilities = $this->facilities;

        if (! is_array($facilities)) {
            return false;
        }

        if (array_is_list($facilities)) {
            return in_array($key, $facilities, true);
        }

        return (bool) data_get($facilities, $key, false);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['logo_path'];
    }

    public function effectiveCommissionPercentage(): float
    {
        if ($this->commission_percentage !== null) {
            return (float) $this->commission_percentage;
        }

        return PlatformSetting::cashierCommissionPercentage();
    }

    public function isCommissionPlan(): bool
    {
        $plan = $this->relationLoaded('subscriptionPlan')
            ? $this->subscriptionPlan
            : $this->subscriptionPlan()->first();

        if (! $plan instanceof SubscriptionPlan) {
            $plan = SubscriptionPlan::query()->where('code', $this->plan_code)->first();
        }

        return $plan?->isCommissionBased() ?? ($this->plan_code === \App\Enums\PlanCode::ManagementKds->value);
    }

    public function isTrialActive(?\DateTimeInterface $now = null): bool
    {
        $now = $now ? \Illuminate\Support\Carbon::parse($now) : now();

        return $this->subscription_status === SubscriptionStatus::Trial
            && $this->trial_ends_at !== null
            && $this->trial_ends_at->isFuture();
    }

    public function hasOverdueCashierInvoice(?\DateTimeInterface $now = null): bool
    {
        if (! $this->isCommissionPlan()) {
            return false;
        }

        $now = $now ? \Illuminate\Support\Carbon::parse($now) : now();
        $currentMonth = $now->format('Y-m');

        return SubscriptionInvoice::query()
            ->where('restaurant_id', $this->id)
            ->where('invoice_type', \App\Enums\InvoiceType::CashierCommission->value)
            ->where('status', '!=', \App\Enums\InvoiceStatus::Paid->value)
            ->where(function ($query) use ($currentMonth, $now) {
                $query->where('period_month', '<', $currentMonth)
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('due_at')->where('due_at', '<=', $now);
                    });
            })
            ->where('amount', '>', 0)
            ->exists();
    }
}
