<div class="mx-auto max-w-md px-5 pb-24 pt-4">
    <p class="customer-section-label">Meja {{ $tableCode ?: '—' }}</p>

    @if ($mode === 'invalid' || $mode === 'blocked')
        <div class="customer-card mt-4 p-6">
            <h1 class="text-2xl font-bold text-body">Belum bisa pesan.</h1>
            <p class="mt-3 text-muted">{{ $message }}</p>
        </div>
    @elseif ($mode === 'claim')
        <div class="customer-card mt-4 p-6">
            <h1 class="text-2xl font-bold text-body">Duduk dulu, lalu isi data.</h1>
            <p class="mt-2 text-sm text-muted">
                @if (! $waChecked)
                    Masukkan nomor WhatsApp Anda untuk memulai pesanan.
                @elseif ($isExistingCustomer)
                    Selamat datang kembali! Periksa data Anda sebelum memesan.
                @else
                    Lengkapi nama Anda sebelum memesan menu.
                @endif
            </p>

            @if ($message)
                <p class="mt-3 rounded-2xl bg-primary/10 px-4 py-3 text-sm text-primary-dark">{{ $message }}</p>
            @endif

            <form wire:submit="claim" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-body">
                        Nomor WhatsApp
                        <input wire:model.live.debounce.300ms="customer_wa" type="tel" inputmode="tel" required
                            class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 text-body focus:border-primary focus:outline-none transition"
                            placeholder="08xxxxxxxxxx">
                    </label>
                    @error('customer_wa')
                        <p class="text-xs text-danger font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if ($waChecked)
                    @if ($isExistingCustomer)
                        <div class="rounded-2xl border border-emerald-500/25 bg-emerald-500/10 p-3.5 text-xs text-emerald-800 dark:text-emerald-300 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Pelanggan Terdaftar: <strong class="font-semibold">{{ $customer_name }}</strong></span>
                            </div>
                            <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">Otomatis Terisi</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-body">
                                Nama
                                <input wire:model="customer_name" type="text" readonly value="{{ $customer_name }}"
                                    class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-raised/70 px-4 py-3 text-body font-medium cursor-not-allowed opacity-90 select-none">
                            </label>
                            <p class="text-[11px] text-muted mt-1">Nama terisi otomatis dari riwayat kunjungan Anda sebelumnya.</p>
                        </div>
                    @else
                        <div class="rounded-2xl border border-amber-500/25 bg-amber-500/10 p-3.5 text-xs text-amber-800 dark:text-amber-300">
                            <div class="flex items-start gap-2">
                                <svg class="h-4 w-4 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-amber-900 dark:text-amber-200">Pelanggan Baru</p>
                                    <p class="mt-0.5 text-slate-600 dark:text-slate-300 leading-relaxed">
                                        Mohon masukkan nama asli atau panggilan yang valid agar pesanan dapat diantarkan dengan tepat ke meja Anda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-body">
                                <span class="flex items-center justify-between">
                                    <span>Nama Lengkap / Panggilan</span>
                                    <span class="text-[11px] text-danger font-semibold">*Wajib</span>
                                </span>
                                <input wire:model.live.debounce.150ms="customer_name" type="text" required placeholder="Contoh: Budi Santoso" maxlength="120"
                                    class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 text-body focus:border-primary focus:outline-none transition">
                            </label>
                            @error('customer_name')
                                <p class="text-xs text-danger font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                @endif

                <button type="submit"
                    @disabled(! $waChecked || (! $isExistingCustomer && blank(trim($customer_name))))
                    class="landing-btn-glow w-full rounded-full bg-primary py-3 font-semibold text-white hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed transition">
                    Lanjut pilih menu
                </button>
            </form>
        </div>
    @elseif ($mode === 'join')
        <div class="customer-card mt-4 p-6">
            <h1 class="text-2xl font-bold text-body">Gabung ke meja ini.</h1>
            <p class="mt-2 text-sm text-muted">{{ $message }}</p>
            <form wire:submit="join" class="mt-6 space-y-4">
                <label class="block text-sm font-medium text-body">
                    PIN 4 digit
                    <input wire:model="join_pin" type="text" inputmode="numeric" maxlength="4" class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 tracking-[0.5em] text-body" placeholder="••••">
                </label>
                <button type="submit" class="landing-btn-glow w-full rounded-full bg-primary py-3 font-semibold text-white hover:bg-primary-dark">Gabung</button>
            </form>
        </div>
    @endif
</div>
