<?php

namespace App\Livewire\Auth;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Models\Facility;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\RestaurantProvisioner;
use App\Services\SubscriptionPlanSync;
use App\Support\AuthGlass;
use App\Support\CmsMedia;
use App\Support\ImageOptimizer;
use App\Support\ReservedSlugs;
use App\Support\RestaurantTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layouts.directory', [
    'title' => 'Daftarkan Restoran Anda - Coba Gratis',
    'description' => 'Daftarkan restoran Anda dan nikmati masa uji coba gratis. Kelola menu digital QR, pesanan meja walk-in, dan pantau omset dengan mudah.',
])]
class RegisterRestaurant extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // Step 1: Akun
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    // Step 2: Restoran
    public string $restaurant_name = '';

    public string $slug = '';

    // Step 3: Paket
    public string $plan_code = '';

    // Step 4: Mode Kasir & Visual Brand (Opsional)
    public bool $simple_mode = false;

    public $logo = null;

    public $hero_banner = null;

    public $qris_image = null;

    public string $primary_color = '#10b981';

    public string $accent_color = '#f59e0b';

    // Step 5: Info Restoran & Lokasi (Opsional)
    public array $category_ids = [];

    public ?int $price_level = 1;

    public array $facilities = [];

    public string $headline = '';

    public string $about_text = '';

    public string $phone = '';

    public string $instagram = '';

    public string $address = '';

    public mixed $latitude = null;

    public mixed $longitude = null;

    public string $opens_at = '10:00';

    public string $closes_at = '22:00';

    public function updatedRestaurantName(): void
    {
        if (filled($this->slug)) {
            return;
        }

        $this->slug = str($this->restaurant_name)->slug()->toString();
    }

    public function updatedLatitude($value): void
    {
        if ($value === '' || $value === null) {
            $this->latitude = null;
        }
    }

    public function updatedLongitude($value): void
    {
        if ($value === '' || $value === null) {
            $this->longitude = null;
        }
    }

    public function nextFromAccount(): void
    {
        $this->validate($this->accountRules());
        $this->step = 2;
    }

    public function nextFromRestaurant(): void
    {
        $this->validate($this->restaurantRules());

        if (blank($this->plan_code)) {
            $this->plan_code = (string) (SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->value('code') ?? '');
        }

        $this->step = 3;
    }

    public function selectPlan(string $planCode): void
    {
        $this->plan_code = $planCode;
    }

    public function nextFromPlan(): void
    {
        $this->validate($this->planRules());
        $this->step = 4;
    }

    public function isLandingOnly(): bool
    {
        return $this->plan_code === PlanCode::LandingOnly->value;
    }

    public function nextFromVisual(): void
    {
        $this->validate($this->visualRules());
        $this->step = 5;
    }

    public function skipVisual(): void
    {
        $this->resetErrorBag(['logo', 'hero_banner', 'qris_image', 'primary_color', 'accent_color']);
        $this->step = 5;
    }

    public function toggleCategory(int $categoryId): void
    {
        if (in_array($categoryId, $this->category_ids, true)) {
            $this->category_ids = array_values(array_diff($this->category_ids, [$categoryId]));
        } else {
            $this->category_ids[] = $categoryId;
        }
    }

    public function toggleFacility(string $facilityKey): void
    {
        if (in_array($facilityKey, $this->facilities, true)) {
            $this->facilities = array_values(array_diff($this->facilities, [$facilityKey]));
        } else {
            $this->facilities[] = $facilityKey;
        }
    }

    public function applyColorPreset(string $primary, string $accent): void
    {
        $this->primary_color = $primary;
        $this->accent_color = $accent;
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function register(RestaurantProvisioner $provisioner, SubscriptionPlanSync $planSync)
    {
        if ($this->latitude === '') {
            $this->latitude = null;
        }
        if ($this->longitude === '') {
            $this->longitude = null;
        }

        $this->validate([
            ...$this->accountRules(),
            ...$this->restaurantRules(),
            ...$this->planRules(),
            ...$this->visualRules(),
            ...$this->infoRules(),
        ]);

        $restaurant = DB::transaction(function () use ($provisioner, $planSync): Restaurant {
            $user = User::query()->create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->uniqueUsername(),
                'password' => $this->password,
                'is_active' => true,
            ]);

            $restaurant = Restaurant::query()->create([
                'name' => $this->restaurant_name,
                'slug' => $this->slug,
                'legal_name' => $this->restaurant_name,
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'is_active' => true,
                'listed_in_directory' => true,
                'landing_enabled' => true,
                'plan_code' => $this->plan_code,
                'subscription_status' => SubscriptionStatus::Trial,
                'trial_ends_at' => now()->addDays(PlatformSetting::trialDays()),
            ]);

            $restaurant->users()->attach($user->id, ['is_active' => true]);

            $provisioner->provision($restaurant, $this->plan_code);
            $planSync->syncOwnerPermissions($restaurant, $this->plan_code);

            // 1. Update Restaurant custom profile data
            $restaurantUpdates = [];
            if ($this->price_level !== null) {
                $restaurantUpdates['price_level'] = $this->price_level;
            }
            if (! empty($this->facilities)) {
                $restaurantUpdates['facilities'] = collect(Facility::knownKeys())
                    ->mapWithKeys(fn (string $key) => [$key => in_array($key, $this->facilities, true)])
                    ->all();
            }
            if ($this->logo) {
                $logoPath = $this->logo->store('restaurants/logos', 'public');
                ImageOptimizer::optimize($logoPath, 512, 512, 1048576, 'public');
                $restaurantUpdates['logo_path'] = $logoPath;
            }
            if ($restaurantUpdates !== []) {
                $restaurant->update($restaurantUpdates);
            }

            if (! empty($this->category_ids)) {
                $restaurant->categories()->sync($this->category_ids);
            }

            // 2. Update Outlet data
            $outlet = $restaurant->fresh()->defaultOutlet;
            if ($outlet) {
                $outletUpdates = [
                    'simple_mode' => $this->isLandingOnly() ? false : (bool) $this->simple_mode,
                ];

                if (filled($this->address)) {
                    $outletUpdates['address'] = $this->address;
                }
                if (filled($this->phone)) {
                    $outletUpdates['phone'] = $this->phone;
                }
                if (filled($this->instagram)) {
                    $outletUpdates['instagram'] = $this->instagram;
                }
                if ($this->latitude !== null) {
                    $outletUpdates['latitude'] = $this->latitude;
                }
                if ($this->longitude !== null) {
                    $outletUpdates['longitude'] = $this->longitude;
                }
                if ($this->qris_image && ! $this->isLandingOnly()) {
                    $qrisPath = $this->qris_image->store('outlets/qris', 'public');
                    ImageOptimizer::optimize($qrisPath, 800, 800, 1048576, 'public');
                    $outletUpdates['qris_image_path'] = $qrisPath;
                }

                $outlet->update($outletUpdates);

                if (filled($this->opens_at) || filled($this->closes_at)) {
                    $opensAt = filled($this->opens_at)
                        ? (strlen($this->opens_at) === 5 ? $this->opens_at.':00' : $this->opens_at)
                        : '10:00:00';
                    $closesAt = filled($this->closes_at)
                        ? (strlen($this->closes_at) === 5 ? $this->closes_at.':00' : $this->closes_at)
                        : '22:00:00';

                    $outlet->operatingHours()->update([
                        'opens_at' => $opensAt,
                        'closes_at' => $closesAt,
                    ]);
                }

                DB::table('outlet_users')->insert([
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                ]);
            }

            // 3. Update CMS Profile
            $cmsProfile = $restaurant->fresh()->cmsProfile;
            if ($cmsProfile) {
                $cmsUpdates = [];
                if (filled($this->headline)) {
                    $cmsUpdates['headline'] = $this->headline;
                }
                if (filled($this->about_text)) {
                    $cmsUpdates['about_html'] = '<p>'.nl2br(e($this->about_text)).'</p>';
                }
                if (filled($this->primary_color)) {
                    $cmsUpdates['primary_color'] = $this->primary_color;
                }
                if (filled($this->accent_color)) {
                    $cmsUpdates['accent_color'] = $this->accent_color;
                }
                if ($this->hero_banner) {
                    $heroPath = $this->hero_banner->store('cms/hero', 'public');
                    ImageOptimizer::optimize($heroPath, 1600, 900, 1048576, 'public');
                    $cmsUpdates['hero_image_path'] = $heroPath;
                }

                if ($this->latitude !== null && $this->longitude !== null) {
                    $cmsUpdates['map_embed_url'] = CmsMedia::mapsEmbedUrl(null, $this->latitude, $this->longitude);
                    $cmsUpdates['cta_url'] = CmsMedia::mapsSearchUrl($this->address, $this->latitude, $this->longitude);
                } elseif (filled($this->address)) {
                    $cmsUpdates['cta_url'] = CmsMedia::mapsSearchUrl($this->address, null, null);
                }

                if ($cmsUpdates !== []) {
                    $cmsProfile->update($cmsUpdates);
                }
            }

            $registrar = app(PermissionRegistrar::class);
            $previousTeamId = $registrar->getPermissionsTeamId();
            $registrar->setPermissionsTeamId($restaurant->id);

            try {
                $user->assignRole('owner');
            } finally {
                $registrar->setPermissionsTeamId($previousTeamId);
            }

            Auth::login($user);

            return $restaurant;
        });

        return redirect()->to(url('/admin/'.$restaurant->slug));
    }

    public function render()
    {
        return view('livewire.auth.register-restaurant', [
            'plans' => SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'categories' => RestaurantCategory::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'availableFacilities' => Facility::activeOptions(),
            'theme' => RestaurantTheme::for(null),
            'trialDays' => PlatformSetting::trialDays(),
            'home' => PlatformSetting::homeViewData(),
            'cssVariables' => AuthGlass::cssVariables(),
            'isLandingOnly' => $this->isLandingOnly(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function accountRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function restaurantRules(): array
    {
        return [
            'restaurant_name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                'unique:restaurants,slug',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (ReservedSlugs::isReserved((string) $value)) {
                        $fail('Slug ini tidak bisa dipakai.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function planRules(): array
    {
        return [
            'plan_code' => ['required', Rule::in(PlanCode::values())],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function visualRules(): array
    {
        return [
            'simple_mode' => ['boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'hero_banner' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:15360'],
            'qris_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function infoRules(): array
    {
        return [
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:restaurant_categories,id'],
            'price_level' => ['nullable', 'integer', 'between:1,4'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string'],
            'headline' => ['nullable', 'string', 'max:191'],
            'about_text' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'opens_at' => ['nullable', 'string', 'max:10'],
            'closes_at' => ['nullable', 'string', 'max:10'],
        ];
    }

    private function uniqueUsername(): string
    {
        $base = str($this->email)->before('@')->slug('_')->limit(40, '')->toString() ?: 'owner';
        $username = $base;
        $i = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.$i;
            $i++;
        }

        return $username;
    }
}
