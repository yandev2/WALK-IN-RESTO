<?php

declare(strict_types=1);

use Bityukov\CommandCenter\Sources\ConfigSource;

return [
    /*
     | Auth guard used when a queued run reloads the actor for authorization,
     | and when history resolves a stored user id to a name.
     |
     | Null keeps the previous behaviour: Auth::getProvider() (the application
     | default guard). Set this when Command Center lives on a Filament panel
     | whose authGuard is not the default — for example 'central' or 'admin'.
     | The Filament pages also pass the current panel's auth guard at dispatch,
     | so this is mainly a fallback for non-panel callers.
     */
    'auth_guard' => null,

    /*
     | Absolute path to the PHP binary used to run Artisan commands.
     | Null means auto-detect via Symfony's PhpExecutableFinder.
     */
    'php_binary' => env('COMMAND_CENTER_PHP_BINARY'),

    /*
     | Working directory for spawned processes. Null means the app base path.
     */
    'working_directory' => null,

    /*
     | Default timeout in seconds applied to commands that do not set their own.
     |
     | This defaults to the same value as max_sync_timeout, because a command
     | that does not specify a timeout may still run synchronously, and a
     | synchronous run cannot outlive the HTTP request that started it. Raise it
     | only for commands you also queue.
     */
    'default_timeout' => 30,

    /*
     | Commands running synchronously may not exceed this timeout, because the
     | web server would terminate the request and orphan the process.
     */
    'max_sync_timeout' => 30,

    /*
     | Shell commands are disabled by default. Enabling this does NOT allow
     | arbitrary commands: shell definitions remain allow-listed and are
     | executed as argument vectors, never as a shell string.
     */
    'shell' => [
        'enabled' => false,
    ],

    /*
     | Command sources, resolved from the container in order. Later sources
     | override earlier ones when two define the same command key.
     |
     | DatabaseSource is opt-in and deliberately not enabled here: anyone who
     | can write its table can run anything the PHP process can. Enable it only
     | with the editor guarded by a strong ability.
     */
    'sources' => [
        ConfigSource::class,
        // \Bityukov\CommandCenter\Sources\DatabaseSource::class,
    ],

    /*
     | Live output.
     |
     | Output is streamed into the cache while a command runs and copied onto
     | the run record when it finishes. The cap bounds a runaway command's log;
     | the head and tail are kept and the middle is dropped.
     */
    'output' => [
        'max_bytes' => 262144,
        'ttl_minutes' => 60,
        'poll_ms' => 750,
    ],

    /*
     | An optional limit applied to every command, on top of any per-command
     | rate limit. Null disables it.
     */
    'rate_limit' => [
        'global' => null,
    ],

    /*
     | Run history.
     |
     | The cache driver needs no migration, which keeps installation to a single
     | composer require. It is capped and TTL-bounded, so treat it as a recent
     | activity log rather than a permanent audit trail — a cache flush clears
     | it. Plan 4 adds a durable database driver.
     */
    'history' => [
        /*
         | 'cache' needs no migration and is capped and TTL-bounded, which makes
         | it a recent-activity log rather than an audit trail — a cache flush
         | clears it. 'database' is durable and needs the published migration.
         */
        'driver' => env('COMMAND_CENTER_HISTORY_DRIVER', 'database'),
        'max' => 100,
        'ttl_hours' => 168,
        'store' => null,
    ],

    /*
     | Gate abilities the package checks for its own destructive actions.
     */
    'abilities' => [
        /*
         | Who sees the module at all: the command catalogue, a run, and the
         | history. Define this gate in your application — an undefined gate
         | denies, so the pages stay hidden until you say who may reach them.
         |
         | Set it to null to make the module visible to everyone who can open
         | the panel. That is a decision, not a default: each command's own
         | 'ability' is then the only thing standing between a panel user and
         | running it.
         */
        'access' => 'command-center:access',
        'prune_history' => 'command-center:prune-history',
        /*
         | Whoever holds this can define what the panel is able to execute.
         | Treat it as deploy access, not as an editor role.
         */
        'manage_commands' => 'command-center:manage-commands',
    ],

    /*
     | Allow-list for the founder panel. Nothing outside this array can run.
     | Destructive deploy commands (migrate, down, up) and long-running workers
     | (queue:work) are omitted on purpose — this catalogue is for monitoring
     | scheduled jobs and recovering failed queue work.
     |
     | Run `php artisan command-center:check` after editing.
     |
     | Keys: run, label, group, help, timeout, queue, ability, confirm,
     | concurrency, rate_limit, progress, variables, flags, type
     | ('artisan' by default, 'shell' if shell execution is enabled).
     */
    'commands' => [
        'ops-expire-stale' => [
            'run' => 'ops:expire-stale',
            'label' => 'Kedaluwarsa operasional',
            'group' => 'Operasional',
            'help' => 'Batalkan antrian kasir dan tutup visit yang kedaluwarsa. Dijadwalkan setiap menit.',
            'queue' => true,
            'timeout' => 60,
        ],
        'subscription-process-lifecycle' => [
            'run' => 'subscription:process-lifecycle',
            'label' => 'Proses siklus langganan',
            'group' => 'Langganan',
            'help' => 'Transisi langganan trial → grace → expired. Dijadwalkan setiap hari pukul 00:05.',
            'queue' => true,
            'timeout' => 60,
        ],
        'subscription-generate-invoices' => [
            'run' => 'subscription:generate-invoices',
            'label' => 'Terbitkan invoice langganan',
            'group' => 'Langganan',
            'help' => 'Terbitkan invoice otomatis H-7 sebelum langganan berakhir. Dijadwalkan setiap hari pukul 00:10.',
            'queue' => true,
            'timeout' => 120,
        ],
        'schedule-list' => [
            'run' => 'schedule:list',
            'label' => 'Daftar jadwal Artisan',
            'group' => 'Jadwal',
            'help' => 'Tampilkan command dan job yang terdaftar di scheduler.',
            'timeout' => 30,
        ],
        'queue-failed' => [
            'run' => 'queue:failed',
            'label' => 'Daftar job gagal',
            'group' => 'Antrian',
            'timeout' => 30,
        ],
        'queue-retry' => [
            'run' => 'queue:retry {id}',
            'label' => 'Ulangi job gagal',
            'group' => 'Antrian',
            'help' => 'Ulangi satu job gagal berdasarkan id, atau "all" untuk semuanya.',
            'timeout' => 30,
            'variables' => [
                'id' => [
                    'label' => 'ID job',
                    'type' => 'text',
                    'default' => 'all',
                    'required' => true,
                ],
            ],
        ],
        'queue-restart' => [
            'run' => 'queue:restart',
            'label' => 'Restart worker antrian',
            'group' => 'Antrian',
            'help' => 'Minta worker berhenti dengan ramah, biasanya setelah deploy.',
            'timeout' => 30,
        ],
        'cache-clear' => [
            'run' => 'cache:clear',
            'label' => 'Bersihkan cache aplikasi',
            'group' => 'Cache',
            'help' => 'Mengosongkan store cache aplikasi.',
            'timeout' => 30,
        ],
        'config-clear' => [
            'run' => 'config:clear',
            'label' => 'Bersihkan cache config',
            'group' => 'Cache',
            'timeout' => 30,
        ],
        'view-clear' => [
            'run' => 'view:clear',
            'label' => 'Bersihkan compiled view',
            'group' => 'Cache',
            'timeout' => 30,
        ],
        'optimize-clear' => [
            'run' => 'optimize:clear',
            'label' => 'Bersihkan semua cache',
            'group' => 'Cache',
            'timeout' => 30,
        ],
        'about' => [
            'run' => 'about',
            'label' => 'Ringkasan aplikasi',
            'group' => 'Diagnostik',
            'timeout' => 30,
        ],
        'migrate-status' => [
            'run' => 'migrate:status',
            'label' => 'Status migrasi',
            'group' => 'Diagnostik',
            'timeout' => 30,
        ],
    ],
];
