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
            <p class="mt-2 text-sm text-muted">Nomor WhatsApp wajib. Nama boleh kosong.</p>
            @if ($message)
                <p class="mt-3 rounded-2xl bg-primary/10 px-4 py-3 text-sm text-primary-dark">{{ $message }}</p>
            @endif
            <form wire:submit="claim" class="mt-6 space-y-4">
                <label class="block text-sm font-medium text-body">
                    Nama
                    <input wire:model="customer_name" type="text" class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 text-body" placeholder="Opsional">
                </label>
                <label class="block text-sm font-medium text-body">
                    WhatsApp
                    <input wire:model="customer_wa" type="tel" inputmode="tel" required class="mt-1 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 text-body" placeholder="08xxxxxxxxxx">
                </label>
                <button type="submit" class="landing-btn-glow w-full rounded-full bg-primary py-3 font-semibold text-white hover:bg-primary-dark">Lanjut pilih menu</button>
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
