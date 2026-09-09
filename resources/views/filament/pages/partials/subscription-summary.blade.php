@php
    $theme = $this->theme();
    $status = $this->statusEnum();
    $isCommission = $this->isCommissionPlan();
    $isOverdue = $this->hasOverdueCashierInvoice();
    $isTrial = $this->isTrialActive();
    $overdueInvoice = $isOverdue ? $this->overdueInvoice() : null;
@endphp

@if ($isOverdue)
    <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 p-4 text-rose-900 shadow-sm dark:border-rose-800/60 dark:bg-rose-950/40 dark:text-rose-200">
        <div class="flex items-start gap-3">
            <div class="rounded-lg bg-rose-500/10 p-2 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="flex-1 text-sm leading-relaxed">
                <h4 class="font-bold text-base text-rose-800 dark:text-rose-100">
                    Layanan Kasir Dinonaktifkan Sementara
                </h4>
                <p class="mt-1">
                    Terdapat tagihan komisi kasir bulan lalu yang belum diselesaikan
                    @if ($overdueInvoice)
                        (Periode: <strong>{{ $overdueInvoice->period_month }}</strong> &bull; Total: <strong>Rp {{ number_format($overdueInvoice->amount, 0, ',', '.') }}</strong>).
                    @else
                        .
                    @endif
                    Menu transaksi POS, Meja, KDS, dan Pengaturan Peran disembunyikan sampai tagihan diselesaikan. <strong>Website publik dan landing page restoran Anda tetap 100% aktif dan dapat diakses pelanggan.</strong>
                </p>
                <p class="mt-1 text-xs text-rose-700 dark:text-rose-300">
                    Silakan unggah bukti transfer pada tabel <strong>Riwayat Invoice</strong> di bawah ini untuk diverifikasi oleh admin.
                </p>
            </div>
        </div>
    </div>
@endif

<div
    class="subscription-plan-card"
    style="--spc-primary: {{ $theme['primary'] }}; --spc-primary-dark: {{ $theme['primary_dark'] }}; --spc-accent: {{ $theme['accent'] }};"
>
    <style>
        .subscription-plan-card {
            --spc-surface: #ffffff;
            --spc-muted: #f8fafc;
            --spc-border: #e2e8f0;
            --spc-text: #0f172a;
            --spc-text-muted: #64748b;
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid var(--spc-border);
            background: var(--spc-surface);
            box-shadow: 0 10px 30px -18px rgb(15 23 42 / 0.28);
        }

        .dark .subscription-plan-card {
            --spc-surface: #111827;
            --spc-muted: #1f2937;
            --spc-border: #374151;
            --spc-text: #f8fafc;
            --spc-text-muted: #94a3b8;
            box-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.55);
        }

        .subscription-plan-card__accent {
            position: absolute;
            inset: 0 auto 0 0;
            width: 0.35rem;
            background: linear-gradient(180deg, var(--spc-primary-dark), var(--spc-primary), var(--spc-accent));
        }

        .subscription-plan-card__inner {
            position: relative;
            padding: 1.35rem 1.5rem 1.35rem 1.75rem;
        }

        .subscription-plan-card__header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .subscription-plan-card__eyebrow {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--spc-text-muted);
        }

        .subscription-plan-card__title {
            margin: 0.25rem 0 0;
            font-size: clamp(1.25rem, 2.2vw, 1.65rem);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: var(--spc-text);
        }

        .subscription-plan-card__badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .subscription-plan-card__badge--success {
            background: color-mix(in srgb, #10b981 16%, white);
            color: #047857;
        }

        .subscription-plan-card__badge--info {
            background: color-mix(in srgb, #0ea5e9 16%, white);
            color: #0369a1;
        }

        .subscription-plan-card__badge--warning {
            background: color-mix(in srgb, #f59e0b 18%, white);
            color: #b45309;
        }

        .subscription-plan-card__badge--danger {
            background: color-mix(in srgb, #f43f5e 16%, white);
            color: #be123c;
        }

        .dark .subscription-plan-card__badge--success {
            background: color-mix(in srgb, #10b981 22%, #1f2937);
            color: #6ee7b7;
        }

        .dark .subscription-plan-card__badge--info {
            background: color-mix(in srgb, #0ea5e9 22%, #1f2937);
            color: #7dd3fc;
        }

        .dark .subscription-plan-card__badge--warning {
            background: color-mix(in srgb, #f59e0b 22%, #1f2937);
            color: #fcd34d;
        }

        .dark .subscription-plan-card__badge--danger {
            background: color-mix(in srgb, #f43f5e 22%, #1f2937);
            color: #fda4af;
        }

        .subscription-plan-card__grid {
            display: grid;
            gap: 0.75rem;
            margin-top: 1.15rem;
            grid-template-columns: 1fr;
        }

        .subscription-plan-card__stat {
            border-radius: 1rem;
            border: 1px solid var(--spc-border);
            background: var(--spc-muted);
            padding: 0.85rem 1rem;
        }

        .subscription-plan-card__stat-label {
            margin: 0;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--spc-text-muted);
        }

        .subscription-plan-card__stat-value {
            margin: 0.35rem 0 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--spc-text);
        }

        .subscription-plan-card__note {
            margin-top: 0.85rem;
            padding: 0.65rem 0.85rem;
            border-radius: 0.75rem;
            font-size: 0.775rem;
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .subscription-plan-card__transfer {
            margin-top: 1rem;
        }

        .subscription-plan-card__transfer .transfer-widget {
            margin-top: 0;
        }

        @media (min-width: 640px) {
            .subscription-plan-card__grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
    </style>

    <div class="subscription-plan-card__accent" aria-hidden="true"></div>

    <div class="subscription-plan-card__inner">
        <div class="subscription-plan-card__header">
            <div>
                <p class="subscription-plan-card__eyebrow">
                    {{ $isCommission ? 'Paket Layanan Kasir & KDS' : 'Paket aktif' }}
                </p>
                <h2 class="subscription-plan-card__title">{{ $this->planName() }}</h2>
            </div>

            @if ($isOverdue)
                <span class="subscription-plan-card__badge subscription-plan-card__badge--danger">
                    Tunggakan Kasir
                </span>
            @elseif ($isCommission && $isTrial)
                <span class="subscription-plan-card__badge subscription-plan-card__badge--info">
                    Masa Uji Coba (Gratis)
                </span>
            @elseif ($isCommission)
                <span class="subscription-plan-card__badge subscription-plan-card__badge--success">
                    Pasca-Bayar Bulanan
                </span>
            @else
                <span class="subscription-plan-card__badge subscription-plan-card__badge--{{ $status?->color() ?? 'info' }}">
                    {{ $this->statusLabel() }}
                </span>
            @endif
        </div>

        @if ($isCommission)
            @if ($isTrial)
                {{-- Trial Mode Display --}}
                <div class="subscription-plan-card__grid">
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Tarif Komisi Saat Ini</p>
                        <p class="subscription-plan-card__stat-value text-emerald-600 dark:text-emerald-400">
                            Bebas Komisi (0%)
                        </p>
                    </div>
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Uji Coba Berakhir</p>
                        <p class="subscription-plan-card__stat-value">{{ $this->expiryLabel() ?? '—' }}</p>
                    </div>
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Sisa Waktu Uji Coba</p>
                        <p class="subscription-plan-card__stat-value">{{ $this->countdownLabel() }}</p>
                    </div>
                </div>

                <div class="subscription-plan-card__note bg-sky-50 text-sky-800 dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200 dark:border-sky-800/50">
                    <svg class="h-4 w-4 shrink-0 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <span>
                        Selama 30 hari pertama, Anda dapat menggunakan layanan kasir & KDS secara gratis tanpa potongan komisi. Tagihan komisi {{ rtrim(rtrim(number_format($this->effectiveCommissionRate(), 2, ',', '.'), '0'), ',') }}% omzet baru berlaku setelah masa uji coba berakhir. Landing page & CMS profil tetap gratis.
                    </span>
                </div>
            @else
                {{-- Active Commission Mode Display --}}
                <div class="subscription-plan-card__grid">
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Omzet Kasir Bulan Ini</p>
                        <p class="subscription-plan-card__stat-value text-indigo-600 dark:text-indigo-400">
                            Rp {{ number_format($this->currentMonthOmzet(), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Tarif Layanan Kasir</p>
                        <p class="subscription-plan-card__stat-value">
                            {{ rtrim(rtrim(number_format($this->effectiveCommissionRate(), 2, ',', '.'), '0'), ',') }}% per transaksi
                        </p>
                    </div>
                    <div class="subscription-plan-card__stat">
                        <p class="subscription-plan-card__stat-label">Estimasi Tagihan Bulan Ini</p>
                        <p class="subscription-plan-card__stat-value text-amber-600 dark:text-amber-400 font-extrabold">
                            Rp {{ number_format($this->currentMonthCommissionEstimate(), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="subscription-plan-card__note bg-slate-50 text-slate-700 dark:bg-slate-900/60 dark:text-slate-300 border border-slate-200 dark:border-slate-800">
                    <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Landing Page 100% Gratis:</strong> Website profil publik dan CMS restoran Anda 100% gratis selamanya tanpa biaya perpanjangan. Bahkan jika layanan kasir ditangguhkan saat jatuh tempo, landing page tetap aktif dan dapat diakses pelanggan.
                    </span>
                </div>
            @endif
        @else
            {{-- Flat Monthly Plan Display --}}
            <div class="subscription-plan-card__grid">
                <div class="subscription-plan-card__stat">
                    <p class="subscription-plan-card__stat-label">Berlaku sampai</p>
                    <p class="subscription-plan-card__stat-value">{{ $this->expiryLabel() ?? '—' }}</p>
                </div>
                <div class="subscription-plan-card__stat">
                    <p class="subscription-plan-card__stat-label">Harga / bulan</p>
                    <p class="subscription-plan-card__stat-value">{{ $this->monthlyPriceLabel() }}</p>
                </div>
                <div class="subscription-plan-card__stat">
                    <p class="subscription-plan-card__stat-label">Sisa masa aktif</p>
                    <p class="subscription-plan-card__stat-value">{{ $this->countdownLabel() }}</p>
                </div>
            </div>
        @endif

        <div class="subscription-plan-card__transfer">
            @include('filament.pages.partials.subscription-transfer-widget', [
                'bankName' => $bankName ?? null,
                'bankAccount' => $bankAccount ?? null,
                'bankHolder' => $bankHolder ?? null,
                'contactEmail' => $contactEmail ?? null,
                'qrUrl' => $qrUrl ?? null,
            ])
        </div>
    </div>
</div>
