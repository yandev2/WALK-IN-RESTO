<?php

namespace App\Services;

use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsGalleryImage;
use App\Models\DiningTable;
use App\Models\ExportFile;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\CmsMedia;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TenantPurgeService
{
    /**
     * Purge a restaurant completely and permanently.
     *
     * @param  int  $restaurantId
     * @param  string  $restaurantName
     * @param  string  $restaurantSlug
     * @param  User|null  $initiator
     * @return array{
     *     success: bool,
     *     restaurant_id: int,
     *     restaurant_name: string,
     *     restaurant_slug: string,
     *     files_deleted: int,
     *     users_purged: int,
     *     message: string
     * }
     *
     * @throws Throwable
     */
    public function purge(
        int $restaurantId,
        string $restaurantName,
        string $restaurantSlug,
        ?User $initiator = null,
    ): array {
        Log::info("Starting permanent force-delete for tenant [ID: {$restaurantId}, Name: {$restaurantName}, Slug: {$restaurantSlug}].");

        // 1. Gather all physical files belonging ONLY to this restaurant
        $filesToPurge = $this->collectPhysicalFiles($restaurantId);

        // 2. Identify attached users and partition them safely
        $orphanedUsers = $this->collectOrphanedUsers($restaurantId);

        // 3. Collect orphaned user avatars into physical files list
        foreach ($orphanedUsers as $orphanedUser) {
            if (filled($orphanedUser->avatar_path) && ! CmsMedia::isExternalUrl($orphanedUser->avatar_path)) {
                $filesToPurge[] = [
                    'disk' => 'public',
                    'path' => $orphanedUser->avatar_path,
                ];
            }
        }

        $usersPurgedCount = count($orphanedUsers);
        $filesDeletedCount = 0;

        // 4. Execute cascading database deletion in strict topological order
        DB::transaction(function () use ($restaurantId, $orphanedUsers): void {
            $this->purgeDatabaseRecords($restaurantId, $orphanedUsers);
        });

        // 5. Delete collected physical files from storage disks
        foreach ($filesToPurge as $file) {
            $disk = $file['disk'] ?? 'public';
            $path = $file['path'] ?? null;

            if (filled($path) && ! CmsMedia::isExternalUrl($path)) {
                try {
                    if (Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                        $filesDeletedCount++;
                    }
                } catch (Throwable $e) {
                    Log::warning("Failed to delete physical file [Disk: {$disk}, Path: {$path}] during tenant purge: {$e->getMessage()}");
                }
            }
        }

        Log::info("Successfully force-deleted tenant [ID: {$restaurantId}, Name: {$restaurantName}]. Files deleted: {$filesDeletedCount}, Users purged: {$usersPurgedCount}.");

        // 6. Send database notification to Founder/Initiator if present
        if ($initiator instanceof User) {
            try {
                $this->notifyInitiator($initiator, $restaurantName, $filesDeletedCount, $usersPurgedCount);
            } catch (Throwable $e) {
                Log::warning("Failed to send completion notification to initiator: {$e->getMessage()}");
            }
        }

        return [
            'success' => true,
            'restaurant_id' => $restaurantId,
            'restaurant_name' => $restaurantName,
            'restaurant_slug' => $restaurantSlug,
            'files_deleted' => $filesDeletedCount,
            'users_purged' => $usersPurgedCount,
            'message' => "Tenant {$restaurantName} ({$restaurantSlug}) berhasil dihapus bersih dari sistem.",
        ];
    }

    /**
     * Collect all physical file paths stored in database rows belonging to this restaurant.
     *
     * @return list<array{disk: string, path: string}>
     */
    protected function collectPhysicalFiles(int $restaurantId): array
    {
        $files = [];

        $add = function (mixed $path, string $disk = 'public') use (&$files): void {
            if (filled($path) && is_string($path) && ! CmsMedia::isExternalUrl($path)) {
                $files[] = ['disk' => $disk, 'path' => $path];
            }
        };

        // Restaurant logo
        $logo = DB::table('restaurants')->where('id', $restaurantId)->value('logo_path');
        $add($logo, 'public');

        // Outlets QRIS
        $outlets = DB::table('outlets')->where('restaurant_id', $restaurantId)->pluck('qris_image_path');
        foreach ($outlets as $path) {
            $add($path, 'public');
        }

        // CMS Profile images
        $cmsProfiles = DB::table('cms_profiles')->where('restaurant_id', $restaurantId)->get([
            'hero_image_path',
            'how_to_image_path',
            'about_image_path',
        ]);
        foreach ($cmsProfiles as $p) {
            $add($p->hero_image_path, 'public');
            $add($p->how_to_image_path, 'public');
            $add($p->about_image_path, 'public');
        }

        // CMS Banners
        $banners = DB::table('cms_banners')->where('restaurant_id', $restaurantId)->pluck('image_path');
        foreach ($banners as $path) {
            $add($path, 'public');
        }

        // CMS Gallery Images
        $gallery = DB::table('cms_gallery_images')->where('restaurant_id', $restaurantId)->pluck('image_path');
        foreach ($gallery as $path) {
            $add($path, 'public');
        }

        // Menu Items (including soft-deleted)
        $menuPhotos = DB::table('menu_items')->where('restaurant_id', $restaurantId)->pluck('photo_path');
        foreach ($menuPhotos as $path) {
            $add($path, 'public');
        }

        // Menu Item Photos (extra gallery photos)
        $extraPhotos = DB::table('menu_item_photos')->where('restaurant_id', $restaurantId)->pluck('photo_path');
        foreach ($extraPhotos as $path) {
            $add($path, 'public');
        }

        // Payments proof images & snapshot
        $payments = DB::table('payments')->where('restaurant_id', $restaurantId)->get([
            'proof_image_path',
            'qris_image_path_snapshot',
        ]);
        foreach ($payments as $pay) {
            $add($pay->proof_image_path, 'public');
            $add($pay->qris_image_path_snapshot, 'public');
        }

        // Order Receipts (stored on 'local' disk: receipts/{public_id}.pdf)
        $receipts = DB::table('order_receipts')->where('restaurant_id', $restaurantId)->pluck('file_path');
        foreach ($receipts as $path) {
            $add($path, 'local');
        }

        // Export files (stored on specific disk)
        $exports = DB::table('export_files')->where('restaurant_id', $restaurantId)->get(['disk', 'file_path']);
        foreach ($exports as $exp) {
            $add($exp->file_path, $exp->disk ?: 'local');
        }

        // Subscription Invoices payment proof (stored on 'local' disk)
        $invoices = DB::table('subscription_invoices')->where('restaurant_id', $restaurantId)->pluck('payment_proof_path');
        foreach ($invoices as $path) {
            $add($path, 'local');
        }

        // WhatsApp messages media (if any)
        $waMedia = DB::table('whatsapp_messages')->where('restaurant_id', $restaurantId)->pluck('media_path');
        foreach ($waMedia as $path) {
            $add($path, 'public');
        }

        return $files;
    }

    /**
     * Identify users attached to this restaurant who do NOT belong to any other restaurant
     * and are NOT Platform Operators (Founders / SuperAdmins).
     *
     * @return list<User>
     */
    protected function collectOrphanedUsers(int $restaurantId): array
    {
        $attachedUserIds = DB::table('restaurant_users')
            ->where('restaurant_id', $restaurantId)
            ->pluck('user_id');

        if ($attachedUserIds->isEmpty()) {
            return [];
        }

        $users = User::query()
            ->whereIn('id', $attachedUserIds)
            ->get();

        $orphaned = [];

        foreach ($users as $user) {
            // NEVER delete platform operators/founders under any circumstances!
            if ($user->isPlatformOperator()) {
                continue;
            }

            // Check if user is bound to any other restaurant
            $hasOtherRestaurant = DB::table('restaurant_users')
                ->where('user_id', $user->id)
                ->where('restaurant_id', '!=', $restaurantId)
                ->exists();

            if (! $hasOtherRestaurant) {
                $orphaned[] = $user;
            }
        }

        return $orphaned;
    }

    /**
     * Delete all records from all 41 tables in strict topological order
     * to satisfy all 6 ON DELETE RESTRICT constraints.
     *
     * @param  list<User>  $orphanedUsers
     */
    protected function purgeDatabaseRecords(int $restaurantId, array $orphanedUsers): void
    {
        // =========================================================================
        // Fase 1: Detail Pesanan & Pembayaran (Daun terdalam relasi order)
        // =========================================================================
        DB::table('order_item_modifiers')->where('restaurant_id', $restaurantId)->delete();
        DB::table('order_items')->where('restaurant_id', $restaurantId)->delete();
        DB::table('payments')->where('restaurant_id', $restaurantId)->delete();
        DB::table('order_receipts')->where('restaurant_id', $restaurantId)->delete();
        DB::table('whatsapp_messages')->where('restaurant_id', $restaurantId)->delete();

        // =========================================================================
        // Fase 2: Pesanan Utama (Membuka RESTRICT orders.visit_id -> visits.id)
        // =========================================================================
        DB::table('orders')->where('restaurant_id', $restaurantId)->delete();

        // =========================================================================
        // Fase 3: Keranjang, Perangkat Kunjungan & Meja
        // (Membuka RESTRICT visits.table_id -> tables.id)
        // =========================================================================
        DB::table('visit_cart_item_modifiers')->where('restaurant_id', $restaurantId)->delete();
        DB::table('visit_cart_items')->where('restaurant_id', $restaurantId)->delete();
        DB::table('visit_devices')->where('restaurant_id', $restaurantId)->delete();
        DB::table('visits')->where('restaurant_id', $restaurantId)->delete();
        DiningTable::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();

        // =========================================================================
        // Fase 4: Shift Kasir & CRM Pelanggan
        // =========================================================================
        DB::table('cashier_shift_movements')->where('restaurant_id', $restaurantId)->delete();
        DB::table('cashier_shifts')->where('restaurant_id', $restaurantId)->delete();
        DB::table('customer_loyalty_points')->where('restaurant_id', $restaurantId)->delete();
        DB::table('restaurant_reviews')->where('restaurant_id', $restaurantId)->delete();
        DB::table('customers')->where('restaurant_id', $restaurantId)->delete();

        // =========================================================================
        // Fase 5: Katalog Menu, Varian, Modifier & Stasiun KDS
        // (Membuka RESTRICT menu_items.station_id -> kds_stations.id)
        // =========================================================================
        DB::table('menu_item_modifier_groups')->where('restaurant_id', $restaurantId)->delete();
        Modifier::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        ModifierGroup::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        DB::table('menu_item_photos')->where('restaurant_id', $restaurantId)->delete();
        MenuVariant::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        MenuItem::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        MenuCategory::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        KdsStation::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();

        // =========================================================================
        // Fase 6: Outlet & Operasional Cabang
        // =========================================================================
        DB::table('outlet_closed_dates')->where('restaurant_id', $restaurantId)->delete();
        DB::table('outlet_operating_hours')->where('restaurant_id', $restaurantId)->delete();
        DB::table('outlet_sequences')->where('restaurant_id', $restaurantId)->delete();
        DB::table('outlet_users')->where('restaurant_id', $restaurantId)->delete();
        DB::table('outlets')->where('restaurant_id', $restaurantId)->delete();

        // =========================================================================
        // Fase 7: CMS, Berkas Ekspor, Invoice Langganan & Log
        // =========================================================================
        CmsBanner::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        CmsFaq::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        CmsGalleryImage::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        DB::table('cms_profiles')->where('restaurant_id', $restaurantId)->delete();
        ExportFile::withTrashed()->where('restaurant_id', $restaurantId)->forceDelete();
        DB::table('subscription_invoices')->where('restaurant_id', $restaurantId)->delete();
        DB::table('restaurant_restaurant_category')->where('restaurant_id', $restaurantId)->delete();
        DB::table('activity_log')->where('restaurant_id', $restaurantId)->delete();

        // =========================================================================
        // Fase 8: Role & Permission Spatie Khusus Tenant
        // =========================================================================
        DB::table('model_has_roles')->where('restaurant_id', $restaurantId)->delete();
        DB::table('model_has_permissions')->where('restaurant_id', $restaurantId)->delete();

        $roleIds = DB::table('roles')->where('restaurant_id', $restaurantId)->pluck('id');
        if ($roleIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('role_id', $roleIds)->delete();
            DB::table('roles')->where('restaurant_id', $restaurantId)->delete();
        }

        // =========================================================================
        // Fase 9: Hubungan Pengguna & Penghapusan Akun Staf Yatim
        // =========================================================================
        DB::table('restaurant_users')->where('restaurant_id', $restaurantId)->delete();

        foreach ($orphanedUsers as $orphanedUser) {
            // Bypass the User::deleting check for restaurant owner using withoutEvents
            User::withoutEvents(function () use ($orphanedUser): void {
                $orphanedUser->forceDelete();
            });
        }

        // =========================================================================
        // Fase 10: Entitas Utama Restoran
        // =========================================================================
        DB::table('restaurants')->where('id', $restaurantId)->delete();
    }

    /**
     * Send completion notification to the Founder / Initiator.
     */
    protected function notifyInitiator(User $initiator, string $restaurantName, int $filesDeleted, int $usersPurged): void
    {
        $body = "Tenant {$restaurantName} telah berhasil dihapus bersih dari sistem. Seluruh 41 tabel relasi database telah dibersihkan, {$filesDeleted} berkas fisik storage telah dihapus, dan {$usersPurged} akun staf eksklusif telah dimusnahkan.";

        Notification::make()
            ->title("Tenant {$restaurantName} Berhasil Dihapus Bersih")
            ->body($body)
            ->success()
            ->sendToDatabase($initiator);
    }
}
