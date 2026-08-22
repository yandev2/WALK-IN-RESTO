<div class="directory-shell flex min-h-screen flex-col bg-surface-base">
    <x-directory.header />

    <div class="landing-container flex-1 py-10">
        <div class="mx-auto max-w-xl">
            <h1 class="font-display text-3xl font-bold text-body">Daftarkan restoran</h1>
            <p class="mt-2 text-sm text-muted">Uji coba {{ $trialDays }} hari. Pilih paket, lalu kelola restoran dari panel admin.</p>

            <div class="mt-6 flex gap-2 text-xs font-semibold">
                <span @class(['rounded-full px-3 py-1', 'bg-primary text-white' => $step === 1, 'bg-surface-muted text-muted' => $step !== 1])>1. Akun</span>
                <span @class(['rounded-full px-3 py-1', 'bg-primary text-white' => $step === 2, 'bg-surface-muted text-muted' => $step !== 2])>2. Restoran</span>
                <span @class(['rounded-full px-3 py-1', 'bg-primary text-white' => $step === 3, 'bg-surface-muted text-muted' => $step !== 3])>3. Paket</span>
            </div>

            <div class="mt-8 space-y-4 rounded-2xl bg-surface-raised p-6 shadow-[var(--card-shadow)] ring-1 ring-[color:var(--border-subtle)]">
                @if ($step === 1)
                    <div>
                        <label class="text-sm font-semibold text-body">Nama</label>
                        <input type="text" wire:model="name" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-body">Email</label>
                        <input type="email" wire:model="email" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-body">Password</label>
                        <input type="password" wire:model="password" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-body">Konfirmasi password</label>
                        <input type="password" wire:model="password_confirmation" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                    </div>
                    <button type="button" wire:click="nextFromAccount" class="w-full rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white">Lanjut</button>
                @endif

                @if ($step === 2)
                    <div>
                        <label class="text-sm font-semibold text-body">Nama restoran</label>
                        <input type="text" wire:model.blur="restaurant_name" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                        @error('restaurant_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-body">Slug URL</label>
                        <input type="text" wire:model="slug" class="mt-1 w-full rounded-xl border border-border-subtle bg-surface-base px-3 py-2 text-sm">
                        <p class="mt-1 text-xs text-muted">Landing: /{{ $slug ?: 'slug' }} · Admin: /admin/{{ $slug ?: 'slug' }}</p>
                        @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="button" wire:click="back" class="rounded-xl bg-surface-muted px-4 py-2.5 text-sm font-semibold text-body">Kembali</button>
                        <button type="button" wire:click="nextFromRestaurant" class="flex-1 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white">Lanjut</button>
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
                                @class([
                                    'block w-full rounded-2xl border p-4 text-left transition',
                                    'border-primary bg-primary/5 ring-2 ring-primary/30' => $selected,
                                    'border-border-subtle hover:border-primary/40' => ! $selected,
                                ])
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex min-w-0 items-start gap-3">
                                        <span @class([
                                            'mt-0.5 inline-flex size-4 shrink-0 items-center justify-center rounded-full border',
                                            'border-primary bg-primary' => $selected,
                                            'border-border-subtle bg-surface-base' => ! $selected,
                                        ])>
                                            @if ($selected)
                                                <span class="size-1.5 rounded-full bg-white"></span>
                                            @endif
                                        </span>
                                        <div>
                                            <p class="font-semibold text-body">{{ $plan->name }}</p>
                                            <p class="mt-1 text-sm text-muted">{{ $plan->description }}</p>
                                        </div>
                                    </div>
                                    <p class="shrink-0 text-sm font-bold text-body">{{ $plan->formattedPrice() }}/bln</p>
                                </div>
                            </button>
                        @endforeach
                        @error('plan_code') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="button" wire:click="back" class="rounded-xl bg-surface-muted px-4 py-2.5 text-sm font-semibold text-body">Kembali</button>
                        <button type="button" wire:click="register" class="flex-1 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white">Mulai uji coba</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-directory.footer />
</div>
