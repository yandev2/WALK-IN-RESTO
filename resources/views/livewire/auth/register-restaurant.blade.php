@php
    $home = $home ?? \App\Models\PlatformSetting::homeViewData();
@endphp

<div
    class="auth-glass-page flex min-h-screen items-center justify-center px-4 py-10"
    @if (filled($cssVariables ?? null))
        style="{{ $cssVariables }}"
    @endif
>
    <div class="auth-glass-card">
        <a href="{{ route('home') }}" class="mb-5 flex items-center justify-center gap-2.5">
            @if (filled($home['logo_url'] ?? null))
                <img
                    src="{{ $home['logo_url'] }}"
                    alt="{{ $home['site_name'] }}"
                    class="h-9 w-auto max-w-[180px] object-contain"
                >
            @else
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="font-display text-lg font-bold text-white">{{ $home['site_name'] }}</span>
            @endif
        </a>

        <h1 class="auth-glass-title font-display text-3xl">Daftarkan restoran</h1>
        <p class="auth-glass-subtitle mt-2 text-sm">Uji coba {{ $trialDays }} hari. Pilih paket, lalu kelola restoran dari panel admin.</p>

        <div class="mt-6 flex justify-center gap-2">
            <span @class(['auth-glass-step', 'is-active' => $step === 1])>1. Akun</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 2])>2. Restoran</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 3])>3. Paket</span>
        </div>

        <div class="mt-8 space-y-4">
            @if ($step === 1)
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Nama</label>
                    <input type="text" wire:model="name" class="auth-glass-input mt-1 w-full text-sm">
                    @error('name') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Email</label>
                    <input type="email" wire:model="email" class="auth-glass-input mt-1 w-full text-sm">
                    @error('email') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Password</label>
                    <input type="password" wire:model="password" class="auth-glass-input mt-1 w-full text-sm">
                    @error('password') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Konfirmasi password</label>
                    <input type="password" wire:model="password_confirmation" class="auth-glass-input mt-1 w-full text-sm">
                </div>
                <button type="button" wire:click="nextFromAccount" class="auth-glass-btn mt-2 w-full px-4 py-2.5 text-sm">Lanjut</button>
            @endif

            @if ($step === 2)
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Nama restoran</label>
                    <input type="text" wire:model.blur="restaurant_name" class="auth-glass-input mt-1 w-full text-sm">
                    @error('restaurant_name') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Slug URL</label>
                    <input type="text" wire:model="slug" class="auth-glass-input mt-1 w-full text-sm">
                    <p class="mt-1 text-xs text-white/70">Landing: /{{ $slug ?: 'slug' }} · Admin: /admin/{{ $slug ?: 'slug' }}</p>
                    @error('slug') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                    <button type="button" wire:click="nextFromRestaurant" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Lanjut</button>
                </div>
            @endif

            @if ($step === 3)
                <div class="space-y-3" role="radiogroup" aria-label="Paket langganan">
                    @foreach ($plans as $plan)
                        @php $selected = $plan_code === $plan->code; @endphp
                        <button
                            type="button"
                            wire:key="plan-{{ $plan->code }}"
                            wire:click="selectPlan('{{ $plan->code }}')"
                            role="radio"
                            aria-checked="{{ $selected ? 'true' : 'false' }}"
                            @class(['auth-glass-plan block w-full p-4 text-left transition', 'is-selected' => $selected])
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex min-w-0 items-start gap-3">
                                    <span @class([
                                        'mt-0.5 inline-flex size-4 shrink-0 items-center justify-center rounded-full border',
                                        'border-white bg-white' => $selected,
                                        'border-white/40 bg-transparent' => ! $selected,
                                    ])>
                                        @if ($selected)
                                            <span class="size-1.5 rounded-full bg-indigo-950"></span>
                                        @endif
                                    </span>
                                    <div>
                                        <p class="font-semibold text-white">{{ $plan->name }}</p>
                                        <p class="mt-1 text-sm text-white/70">{{ $plan->description }}</p>
                                    </div>
                                </div>
                                <p class="shrink-0 text-sm font-bold text-white">{{ $plan->formattedPrice() }}/bln</p>
                            </div>
                        </button>
                    @endforeach
                    @error('plan_code') <p class="auth-glass-error text-xs">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                    <button type="button" wire:click="register" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Mulai uji coba</button>
                </div>
            @endif
        </div>

        <p class="auth-glass-subtitle mt-6 text-center text-sm">
            Sudah punya akun? <a href="{{ url('/admin/login') }}">Masuk</a>
        </p>
    </div>
</div>
