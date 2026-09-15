<?php

namespace App\Console\Commands;

use App\Jobs\ForceDeleteTenantJob;
use App\Models\Restaurant;
use App\Services\TenantPurgeService;
use Illuminate\Console\Command;

class TenantForceDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:force-delete {slug : Slug tenant yang akan dihapus} {--force : Lewati konfirmasi interaktif} {--sync : Jalankan secara sinkron tanpa antrian queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus bersih dan permanen sebuah tenant beserta seluruh relasi data (41 tabel), berkas fisik, dan akun staf yatim.';

    public function handle(TenantPurgeService $purgeService): int
    {
        $slug = (string) $this->argument('slug');

        $restaurant = Restaurant::query()->where('slug', $slug)->first();

        if (! $restaurant instanceof Restaurant) {
            $this->error("Tenant dengan slug [{$slug}] tidak ditemukan.");
            return self::FAILURE;
        }

        $id = $restaurant->id;
        $name = $restaurant->name;

        $this->warn("=================================================================");
        $this->warn("PERINGATAN KERAS: TINDAKAN INI BERSIFAT PERMANEN & DESTRUKTIF!");
        $this->warn("Tenant: {$name} (ID: {$id}, Slug: {$slug})");
        $this->warn("Seluruh data pesanan, transaksi, menu, meja, berkas gambar/PDF,");
        $this->warn("dan akun staf eksklusif akan dimusnahkan secara permanen.");
        $this->warn("=================================================================");

        if (! $this->option('force') && ! $this->confirm("Ketik 'yes' jika Anda benar-benar yakin ingin menghapus permanen tenant ini?", false)) {
            $this->info("Operasi dibatalkan.");
            return self::SUCCESS;
        }

        // Deactivate immediately
        $restaurant->forceFill([
            'is_active' => false,
            'listed_in_directory' => false,
            'landing_enabled' => false,
        ])->save();

        if ($this->option('sync')) {
            $this->info("Menjalankan pembersihan tenant secara sinkron...");
            $result = $purgeService->purge($id, $name, $slug);
            $this->info("Pembersihan selesai! {$result['files_deleted']} berkas terhapus, {$result['users_purged']} akun staf dibersihkan.");
        } else {
            ForceDeleteTenantJob::dispatch($id, $name, $slug);
            $this->info("Job penghapusan [{$name}] berhasil dimasukkan ke dalam antrian queue background.");
        }

        return self::SUCCESS;
    }
}
