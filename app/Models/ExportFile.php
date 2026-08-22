<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportFile extends Model
{
    use BelongsToRestaurantAndOutlet;
    use SoftDeletes;

    public const STATUS_QUEUED = 'queued';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const FORMAT_EXCEL = 'excel';

    public const FORMAT_PDF = 'pdf';

    public const MODULE_PENJUALAN_ITEM = 'penjualan_item';

    public const MODULE_PENJUALAN_ORDER = 'penjualan_order';

    public const MODULE_VOID_ITEM = 'void_item';

    public const MODULE_VOID_ORDER = 'void_order';

    public const MODULE_OMZET_HARIAN = 'omzet_harian';

    public const MODULE_KATALOG_MENU = 'katalog_menu';

    /**
     * @return array<string, string>
     */
    public static function moduleLabels(): array
    {
        return [
            self::MODULE_PENJUALAN_ITEM => 'Daftar penjualan (item)',
            self::MODULE_PENJUALAN_ORDER => 'Daftar penjualan (order)',
            self::MODULE_VOID_ITEM => 'Laporan void item',
            self::MODULE_VOID_ORDER => 'Laporan void pesanan',
            self::MODULE_OMZET_HARIAN => 'Ringkasan omzet harian',
            self::MODULE_KATALOG_MENU => 'Katalog item menu',
        ];
    }

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'filename',
        'file_path',
        'disk',
        'mime_type',
        'file_size',
        'module',
        'format',
        'status',
        'filters',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getReadableSizeAttribute(): string
    {
        if (! $this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $this->file_size > 0 ? (int) floor(log($this->file_size, 1024)) : 0;
        $power = min($power, count($units) - 1);

        return number_format($this->file_size / (1024 ** $power), 2, ',', '.').' '.$units[$power];
    }

    public function getModuleLabelAttribute(): string
    {
        return self::moduleLabels()[$this->module] ?? $this->module;
    }

    public function downloadUrl(): string
    {
        return route('export-files.download', $this);
    }

    public function isDownloadable(): bool
    {
        return $this->status === self::STATUS_COMPLETED && filled($this->file_path);
    }
}
