@php
    $home = $home ?? \App\Models\PlatformSetting::homeViewData();
@endphp

<div
    class="auth-glass-page flex min-h-screen items-center justify-center px-4 py-10"
    @if (filled($cssVariables ?? null))
        style="{{ $cssVariables }}"
    @endif
>
    <div @class([
        'auth-glass-card transition-all duration-300',
        '!max-w-2xl' => in_array($step, [4, 5]),
    ])>
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

        <div class="mt-6 flex flex-wrap justify-center gap-1.5 sm:gap-2">
            <span @class(['auth-glass-step', 'is-active' => $step === 1])>1. Akun</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 2])>2. Restoran</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 3])>3. Paket</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 4])>{{ $isLandingOnly ? '4. Tampilan' : '4. Mode & Tampilan' }}</span>
            <span @class(['auth-glass-step', 'is-active' => $step === 5])>5. Info & Lokasi</span>
        </div>

        <div class="mt-8 space-y-4">
            {{-- STEP 1: AKUN OWNER --}}
            @if ($step === 1)
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="auth-glass-input mt-1 w-full text-sm" placeholder="Nama Anda">
                    @error('name') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Email Bisnis</label>
                    <input type="email" wire:model="email" class="auth-glass-input mt-1 w-full text-sm" placeholder="nama@restoran.com">
                    @error('email') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Password</label>
                    <input type="password" wire:model="password" class="auth-glass-input mt-1 w-full text-sm" placeholder="Minimal 8 karakter">
                    @error('password') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Konfirmasi Password</label>
                    <input type="password" wire:model="password_confirmation" class="auth-glass-input mt-1 w-full text-sm" placeholder="Ulangi password">
                </div>
                <button type="button" wire:click="nextFromAccount" class="auth-glass-btn mt-2 w-full px-4 py-2.5 text-sm">Lanjut</button>
            @endif

            {{-- STEP 2: IDENTITAS RESTORAN --}}
            @if ($step === 2)
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Nama Restoran / Cafe</label>
                    <input type="text" wire:model.live.debounce.300ms="restaurant_name" class="auth-glass-input mt-1 w-full text-sm" placeholder="Contoh: Kopi Nusantara">
                    @error('restaurant_name') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Slug URL</label>
                    <input type="text" wire:model.blur="slug" class="auth-glass-input mt-1 w-full text-sm" placeholder="kopi-nusantara">
                    <p class="mt-1 text-xs text-white/70">Landing Page: /{{ $slug ?: 'slug' }} · Panel Admin: /admin/{{ $slug ?: 'slug' }}</p>
                    @error('slug') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                    <button type="button" wire:click="nextFromRestaurant" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Lanjut</button>
                </div>
            @endif

            {{-- STEP 3: PILIH PAKET --}}
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
                                <div class="shrink-0 text-right">
                                    @if ($plan->isCommissionBased())
                                        <span class="inline-block rounded-md bg-emerald-400/20 border border-emerald-300/40 px-2 py-0.5 text-xs font-bold text-emerald-300">
                                            Bebas Biaya Bulanan
                                        </span>
                                        <p class="mt-1 text-xs font-semibold text-white/90">Komisi {{ $plan->formattedPrice() }}</p>
                                    @else
                                        <p class="text-sm font-bold text-white">{{ $plan->formattedPrice() }}/bln</p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                    @error('plan_code') <p class="auth-glass-error text-xs">{{ $message }}</p> @enderror

                    <div class="rounded-xl border border-emerald-400/30 bg-emerald-950/40 p-3.5 text-xs text-emerald-200">
                        <div class="flex items-start gap-2.5">
                            <svg class="size-4 shrink-0 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <strong>Landing Page 100% Gratis:</strong> Restoran Anda otomatis mendapatkan website profil publik dan CMS tanpa biaya perpanjangan selamanya. Layanan kasir hanya dikenakan komisi omzet di akhir bulan setelah masa uji coba 30 hari berakhir.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <div class="flex gap-2">
                        <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                        <button type="button" wire:click="nextFromPlan" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Lanjut ke Tampilan Resto &rarr;</button>
                    </div>
                    <button type="button" wire:click="register" class="mt-1 text-center text-xs text-white/75 underline hover:text-white transition">
                        Lewati kustomisasi tampilan & langsung mulai uji coba
                    </button>
                </div>
            @endif

            {{-- STEP 4: MODE KASIR & VISUAL BRAND (OPSIONAL) --}}
            @if ($step === 4)
                <div class="flex items-start gap-2.5 rounded-xl border border-white/20 bg-white/10 p-3 text-xs text-white/90">
                    <svg class="size-4 shrink-0 text-white/80 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                    </svg>
                    <div>
                        @if ($isLandingOnly)
                            <strong>Langkah Opsional:</strong> Tentukan logo, banner, dan warna tema landing page restoran Anda. Anda juga bisa mengaturnya nanti di Panel Admin.
                        @else
                            <strong>Langkah Opsional:</strong> Tentukan alur kasir dan tampilan visual restoran Anda. Anda juga bisa mengaturnya nanti di Panel Admin.
                        @endif
                    </div>
                </div>

                {{-- Mode Kasir Selection (Hidden for Landing Only Plan) --}}
                @if (! $isLandingOnly)
                    <div class="space-y-2">
                        <label class="auth-glass-label text-sm font-semibold">Pilih Mode Operasional Kasir</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div
                                wire:click="$set('simple_mode', false)"
                                role="button"
                                tabindex="0"
                                @class([
                                    'p-4 rounded-xl border transition text-left cursor-pointer flex flex-col justify-between',
                                    'border-white bg-white/25 shadow-lg ring-1 ring-white/50' => ! $simple_mode,
                                    'border-white/20 bg-white/5 hover:bg-white/10' => $simple_mode,
                                ])
                            >
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-sm text-white">Mode Normal</span>
                                        <span class="rounded-full bg-indigo-500/40 px-2 py-0.5 text-[10px] font-semibold text-indigo-200 border border-indigo-400/30">Dine-in Resto</span>
                                    </div>
                                    <p class="mt-2 text-xs leading-relaxed text-white/80">
                                        Cocok untuk restoran dengan alur terstruktur: Pesan &rarr; Antrean Dapur (KDS) &rarr; Masak & Saji &rarr; Meja Siap.
                                    </p>
                                </div>
                                <div class="mt-3 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-300">
                                    @if (! $simple_mode)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        <span>Aktif Terpilih</span>
                                    @else
                                        <span class="text-white/50">Klik untuk pilih</span>
                                    @endif
                                </div>
                            </div>

                            <div
                                wire:click="$set('simple_mode', true)"
                                role="button"
                                tabindex="0"
                                @class([
                                    'p-4 rounded-xl border transition text-left cursor-pointer flex flex-col justify-between',
                                    'border-amber-300 bg-amber-500/20 shadow-lg ring-1 ring-amber-300/60' => $simple_mode,
                                    'border-white/20 bg-white/5 hover:bg-white/10' => ! $simple_mode,
                                ])
                            >
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-sm text-amber-200">Mode Simple</span>
                                        <span class="rounded-full bg-amber-400/30 px-2 py-0.5 text-[10px] font-semibold text-amber-100 border border-amber-300/40">Prasmanan / Cepat</span>
                                    </div>
                                    <p class="mt-2 text-xs leading-relaxed text-white/80">
                                        Cocok untuk prasmanan, lesehan, & fast-food: Kasir input total & bayar &rarr; Otomatis selesai tanpa step dapur. Meja langsung ready.
                                    </p>
                                </div>
                                <div class="mt-3 flex items-center gap-1.5 text-[11px] font-semibold text-amber-300">
                                    @if ($simple_mode)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        <span>Aktif Terpilih</span>
                                    @else
                                        <span class="text-white/50">Klik untuk pilih</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Upload Media Visual --}}
                @if (! $isLandingOnly)
                    {{-- Management / POS Plan: Logo & QRIS side-by-side, Banner below --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-white/20 bg-white/10 p-3.5 flex flex-col justify-between">
                            <div>
                                <label class="auth-glass-label block text-sm font-semibold">Logo Restoran (1:1)</label>
                                <p class="text-[11px] text-white/60 mb-2">Format JPG, PNG, WEBP (disarankan kotak min. 300x300 px).</p>
                                <input type="file" wire:model="logo" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-white/80 file:mr-2 file:rounded-lg file:border-0 file:bg-white/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-white/30 cursor-pointer">
                            </div>
                            @if ($logo && method_exists($logo, 'temporaryUrl'))
                                <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 p-1.5">
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo" class="h-9 w-9 rounded-md object-cover border border-white/30 shrink-0">
                                    <span class="text-[11px] text-emerald-200 font-medium">Logo siap disimpan</span>
                                </div>
                            @endif
                            @error('logo') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="rounded-xl border border-white/20 bg-white/10 p-3.5 flex flex-col justify-between">
                            <div>
                                <label class="auth-glass-label block text-sm font-semibold">QRIS Pembayaran Outlet</label>
                                <p class="text-[11px] text-white/60 mb-2">Gambar QRIS untuk kasir & pembayaran digital tamu.</p>
                                <input type="file" wire:model="qris_image" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-white/80 file:mr-2 file:rounded-lg file:border-0 file:bg-white/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-white/30 cursor-pointer">
                            </div>
                            @if ($qris_image && method_exists($qris_image, 'temporaryUrl'))
                                <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 p-1.5">
                                    <img src="{{ $qris_image->temporaryUrl() }}" alt="Preview QRIS" class="h-9 w-9 rounded-md object-contain bg-white p-0.5 shrink-0">
                                    <span class="text-[11px] text-emerald-200 font-medium">QRIS siap disimpan</span>
                                </div>
                            @endif
                            @error('qris_image') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-white/20 bg-white/10 p-3.5">
                        <label class="auth-glass-label block text-sm font-semibold">Banner Utama / Hero Landing (16:9)</label>
                        <p class="text-[11px] text-white/60 mb-2">Rasio 16:9, otomatis dipotong dan dioptimasi (maks 15 MB).</p>
                        <input type="file" wire:model="hero_banner" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-white/80 file:mr-2 file:rounded-lg file:border-0 file:bg-white/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-white/30 cursor-pointer">
                        @if ($hero_banner && method_exists($hero_banner, 'temporaryUrl'))
                            <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 p-1.5">
                                <img src="{{ $hero_banner->temporaryUrl() }}" alt="Preview Banner" class="h-10 w-16 rounded-md object-cover border border-white/30 shrink-0">
                                <span class="text-[11px] text-emerald-200 font-medium">Banner siap disimpan</span>
                            </div>
                        @endif
                        @error('hero_banner') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- Landing Only Plan: Logo & Hero Banner side-by-side (QRIS completely hidden) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-white/20 bg-white/10 p-3.5 flex flex-col justify-between">
                            <div>
                                <label class="auth-glass-label block text-sm font-semibold">Logo Restoran (1:1)</label>
                                <p class="text-[11px] text-white/60 mb-2">Format JPG, PNG, WEBP (disarankan kotak min. 300x300 px).</p>
                                <input type="file" wire:model="logo" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-white/80 file:mr-2 file:rounded-lg file:border-0 file:bg-white/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-white/30 cursor-pointer">
                            </div>
                            @if ($logo && method_exists($logo, 'temporaryUrl'))
                                <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 p-1.5">
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo" class="h-9 w-9 rounded-md object-cover border border-white/30 shrink-0">
                                    <span class="text-[11px] text-emerald-200 font-medium">Logo siap disimpan</span>
                                </div>
                            @endif
                            @error('logo') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="rounded-xl border border-white/20 bg-white/10 p-3.5 flex flex-col justify-between">
                            <div>
                                <label class="auth-glass-label block text-sm font-semibold">Banner Utama Landing (16:9)</label>
                                <p class="text-[11px] text-white/60 mb-2">Rasio 16:9, otomatis dipotong & dikompres (maks 15 MB).</p>
                                <input type="file" wire:model="hero_banner" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-white/80 file:mr-2 file:rounded-lg file:border-0 file:bg-white/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-white/30 cursor-pointer">
                            </div>
                            @if ($hero_banner && method_exists($hero_banner, 'temporaryUrl'))
                                <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 p-1.5">
                                    <img src="{{ $hero_banner->temporaryUrl() }}" alt="Preview Banner" class="h-9 w-14 rounded-md object-cover border border-white/30 shrink-0">
                                    <span class="text-[11px] text-emerald-200 font-medium">Banner siap disimpan</span>
                                </div>
                            @endif
                            @error('hero_banner') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Brand Colors --}}
                <div class="rounded-xl border border-white/20 bg-white/10 p-3.5 space-y-3">
                    <div>
                        <label class="auth-glass-label block text-sm font-semibold">Warna Brand Restoran</label>
                        <p class="text-[11px] text-white/60 mb-2">Pilih palet cepat atau atur kode warna HEX kustom sesuai brand Anda.</p>
                        @php
                            $normPri = strtoupper(trim($primary_color));
                            $normAcc = strtoupper(trim($accent_color));
                        @endphp
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                wire:click="applyColorPreset('#10b981', '#f59e0b')"
                                @class([
                                    'flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-xs transition',
                                    'border-white bg-white/30 text-white shadow-md ring-1 ring-white/50 font-bold' => $normPri === '#10B981' && $normAcc === '#F59E0B',
                                    'border-white/20 bg-white/10 text-white/90 hover:bg-white/20' => ! ($normPri === '#10B981' && $normAcc === '#F59E0B'),
                                ])
                            >
                                <span class="size-3 rounded-full bg-[#10b981] ring-1 ring-white/40"></span>
                                <span class="size-3 rounded-full bg-[#f59e0b] ring-1 ring-white/40"></span>
                                <span>Emerald & Amber</span>
                            </button>
                            <button
                                type="button"
                                wire:click="applyColorPreset('#6366f1', '#ec4899')"
                                @class([
                                    'flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-xs transition',
                                    'border-white bg-white/30 text-white shadow-md ring-1 ring-white/50 font-bold' => $normPri === '#6366F1' && $normAcc === '#EC4899',
                                    'border-white/20 bg-white/10 text-white/90 hover:bg-white/20' => ! ($normPri === '#6366F1' && $normAcc === '#EC4899'),
                                ])
                            >
                                <span class="size-3 rounded-full bg-[#6366f1] ring-1 ring-white/40"></span>
                                <span class="size-3 rounded-full bg-[#ec4899] ring-1 ring-white/40"></span>
                                <span>Indigo & Pink</span>
                            </button>
                            <button
                                type="button"
                                wire:click="applyColorPreset('#ef4444', '#f97316')"
                                @class([
                                    'flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-xs transition',
                                    'border-white bg-white/30 text-white shadow-md ring-1 ring-white/50 font-bold' => $normPri === '#EF4444' && $normAcc === '#F97316',
                                    'border-white/20 bg-white/10 text-white/90 hover:bg-white/20' => ! ($normPri === '#EF4444' && $normAcc === '#F97316'),
                                ])
                            >
                                <span class="size-3 rounded-full bg-[#ef4444] ring-1 ring-white/40"></span>
                                <span class="size-3 rounded-full bg-[#f97316] ring-1 ring-white/40"></span>
                                <span>Merah & Orange</span>
                            </button>
                            <button
                                type="button"
                                wire:click="applyColorPreset('#d97706', '#475569')"
                                @class([
                                    'flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-xs transition',
                                    'border-white bg-white/30 text-white shadow-md ring-1 ring-white/50 font-bold' => $normPri === '#D97706' && $normAcc === '#475569',
                                    'border-white/20 bg-white/10 text-white/90 hover:bg-white/20' => ! ($normPri === '#D97706' && $normAcc === '#475569'),
                                ])
                            >
                                <span class="size-3 rounded-full bg-[#d97706] ring-1 ring-white/40"></span>
                                <span class="size-3 rounded-full bg-[#475569] ring-1 ring-white/40"></span>
                                <span>Amber & Slate</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <span class="text-xs text-white/80 font-medium">Warna Utama (Primary)</span>
                            <div class="mt-1 flex items-center rounded-xl border border-white/30 bg-white/20 px-2 py-1 focus-within:border-white focus-within:ring-2 focus-within:ring-white/40 transition">
                                <input type="color" wire:model.live="primary_color" class="h-7 w-7 rounded-lg border-0 bg-transparent cursor-pointer p-0 shrink-0">
                                <input type="text" wire:model="primary_color" class="bg-transparent border-0 text-white font-mono text-xs uppercase px-2 py-0.5 focus:outline-none w-full" maxlength="7">
                            </div>
                            @error('primary_color') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <span class="text-xs text-white/80 font-medium">Warna Aksen (Accent)</span>
                            <div class="mt-1 flex items-center rounded-xl border border-white/30 bg-white/20 px-2 py-1 focus-within:border-white focus-within:ring-2 focus-within:ring-white/40 transition">
                                <input type="color" wire:model.live="accent_color" class="h-7 w-7 rounded-lg border-0 bg-transparent cursor-pointer p-0 shrink-0">
                                <input type="text" wire:model="accent_color" class="bg-transparent border-0 text-white font-mono text-xs uppercase px-2 py-0.5 focus:outline-none w-full" maxlength="7">
                            </div>
                            @error('accent_color') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 4 Navigation --}}
                <div class="flex gap-2 pt-2">
                    <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                    <button type="button" wire:click="skipVisual" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Lewati Langkah Ini</button>
                    <button type="button" wire:click="nextFromVisual" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Lanjut ke Info & Lokasi &rarr;</button>
                </div>
            @endif

            {{-- STEP 5: KARAKTERISTIK, KONTEN & LOKASI (OPSIONAL) --}}
            @if ($step === 5)
                <div class="flex items-start gap-2.5 rounded-xl border border-white/20 bg-white/10 p-3 text-xs text-white/90">
                    <svg class="size-4 shrink-0 text-white/80 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <div>
                        <strong>Langkah Opsional:</strong> Lengkapi alamat, jam buka, dan cerita resto untuk mempercantik profil landing page Anda.
                    </div>
                </div>

                {{-- Kategori Restoran --}}
                @if ($categories->isNotEmpty())
                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Kategori Restoran</label>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($categories as $cat)
                                @php $isCatSelected = in_array($cat->id, $category_ids, true); @endphp
                                <button
                                    type="button"
                                    wire:key="cat-{{ $cat->id }}"
                                    wire:click="toggleCategory({{ $cat->id }})"
                                    @class([
                                        'rounded-full px-3 py-1 text-xs font-semibold transition',
                                        'bg-white text-indigo-950 shadow-md ring-2 ring-white/50' => $isCatSelected,
                                        'bg-white/15 text-white/90 hover:bg-white/25 border border-white/20' => ! $isCatSelected,
                                    ])
                                >
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Level Harga --}}
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Level Harga Rata-rata</label>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @foreach ([1 => ['$', 'Ekonomis (<25k)'], 2 => ['$$', 'Terjangkau (25-50k)'], 3 => ['$$$', 'Menengah (50-100k)'], 4 => ['$$$$', 'Premium (>100k)']] as $lvl => [$sym, $desc])
                            <button
                                type="button"
                                wire:key="price-{{ $lvl }}"
                                wire:click="$set('price_level', {{ $lvl }})"
                                @class([
                                    'p-2 rounded-xl text-center border transition',
                                    'border-white bg-white text-indigo-950 font-bold shadow-md' => $price_level === $lvl,
                                    'border-white/20 bg-white/10 text-white/90 hover:bg-white/20' => $price_level !== $lvl,
                                ])
                            >
                                <div class="text-sm font-extrabold">{{ $sym }}</div>
                                <div class="text-[10px] opacity-80">{{ $desc }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Fasilitas --}}
                @if (!empty($availableFacilities))
                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Fasilitas Restoran</label>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($availableFacilities as $facKey => $facName)
                                @php $isFacSelected = in_array($facKey, $facilities, true); @endphp
                                <button
                                    type="button"
                                    wire:key="fac-{{ $facKey }}"
                                    wire:click="toggleFacility('{{ $facKey }}')"
                                    @class([
                                        'rounded-lg px-2.5 py-1 text-xs font-semibold transition flex items-center gap-1.5',
                                        'bg-emerald-400 text-emerald-950 shadow-md font-bold' => $isFacSelected,
                                        'bg-white/15 text-white/90 hover:bg-white/25 border border-white/20' => ! $isFacSelected,
                                    ])
                                >
                                    @if ($isFacSelected)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    @endif
                                    <span>{{ $facName }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Slogan & Cerita Tentang --}}
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Slogan / Headline Landing</label>
                        <input type="text" wire:model="headline" class="auth-glass-input mt-1 w-full text-sm" placeholder="Contoh: Pengalaman Rasa Terbaik untuk Keluarga Anda">
                        @error('headline') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Cerita Singkat / Tentang Kami</label>
                        <textarea wire:model="about_text" rows="2" class="auth-glass-input mt-1 w-full text-sm resize-none" placeholder="Tuliskan cerita singkat tentang menu andalan, bahan segar, atau suasana resto..."></textarea>
                        @error('about_text') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Kontak & Alamat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Nomor WhatsApp Resto</label>
                        <input type="text" wire:model="phone" class="auth-glass-input mt-1 w-full text-sm" placeholder="08123456789">
                        @error('phone') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="auth-glass-label text-sm font-semibold">Instagram Resto (opsional)</label>
                        <input type="text" wire:model="instagram" class="auth-glass-input mt-1 w-full text-sm" placeholder="@namaresto atau link profil">
                        @error('instagram') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Alamat Lengkap Outlet</label>
                    <input type="text" wire:model="address" class="auth-glass-input mt-1 w-full text-sm" placeholder="Jl. Sudirman No. 12, Bandung">
                    @error('address') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- GPS Coordinates with Auto-Detect Button --}}
                <div class="rounded-xl border border-white/20 bg-white/10 p-3 space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <span class="text-sm font-semibold text-white">Koordinat Lokasi GPS</span>
                            <p class="text-[11px] text-white/70">Otomatis menyusun URL Google Maps & navigasi arah di landing page.</p>
                        </div>
                        <button
                            type="button"
                            x-data="{ detecting: false }"
                            @click="
                                if (!navigator.geolocation) {
                                    alert('Browser Anda tidak mendukung deteksi lokasi GPS.');
                                    return;
                                }
                                detecting = true;
                                navigator.geolocation.getCurrentPosition(
                                    (position) => {
                                        detecting = false;
                                        $wire.set('latitude', Number(position.coords.latitude.toFixed(7)));
                                        $wire.set('longitude', Number(position.coords.longitude.toFixed(7)));
                                    },
                                    (error) => {
                                        detecting = false;
                                        alert('Gagal mendeteksi lokasi: ' + (error.message || 'Izin lokasi tidak diberikan.'));
                                    },
                                    { enableHighAccuracy: true, timeout: 10000 }
                                );
                            "
                            class="auth-glass-btn-muted flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold hover:bg-white/25 transition shrink-0"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-text="detecting ? 'Mendeteksi...' : 'Gunakan Lokasi Saat Ini (GPS)'"></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] text-white/80">Latitude</label>
                            <input type="number" step="any" wire:model.blur="latitude" class="auth-glass-input mt-1 w-full text-xs" placeholder="-6.917464">
                            @error('latitude') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] text-white/80">Longitude</label>
                            <input type="number" step="any" wire:model.blur="longitude" class="auth-glass-input mt-1 w-full text-xs" placeholder="107.619123">
                            @error('longitude') <p class="auth-glass-error mt-1 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if (filled($latitude) && filled($longitude))
                        <div class="mt-1 flex items-center gap-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 px-2.5 py-1.5 text-xs text-emerald-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-emerald-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Titik GPS aktif ({{ $latitude }}, {{ $longitude }}). Embed peta & CTA navigasi rute akan otomatis terisi.</span>
                        </div>
                    @endif
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <label class="auth-glass-label text-sm font-semibold">Jam Operasional Harian</label>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <div>
                            <span class="text-[11px] text-white/80">Jam Buka</span>
                            <input type="time" wire:model="opens_at" class="auth-glass-input mt-1 w-full text-xs">
                        </div>
                        <div>
                            <span class="text-[11px] text-white/80">Jam Tutup</span>
                            <input type="time" wire:model="closes_at" class="auth-glass-input mt-1 w-full text-xs">
                        </div>
                    </div>
                </div>

                {{-- Step 5 Navigation --}}
                <div class="flex gap-2 pt-2">
                    <button type="button" wire:click="back" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Kembali</button>
                    <button type="button" wire:click="register" class="auth-glass-btn-muted px-4 py-2.5 text-sm">Lewati & Selesai</button>
                    <button type="button" wire:click="register" class="auth-glass-btn flex-1 px-4 py-2.5 text-sm">Simpan & Selesaikan Pendaftaran</button>
                </div>
            @endif
        </div>

        <p class="auth-glass-subtitle mt-6 text-center text-sm">
            Sudah punya akun? <a href="{{ url('/admin/login') }}" rel="nofollow">Masuk</a>
        </p>
    </div>
</div>
