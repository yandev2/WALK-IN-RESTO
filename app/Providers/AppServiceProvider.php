<?php

namespace App\Providers;

use App\Livewire\Hooks\BlockGraceMutations;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use App\Models\ExportFile;
use App\Models\Role;
use App\Models\User;
use App\Observers\BlogCommentObserver;
use App\Observers\BlogPostObserver;
use App\Observers\BlogPostTranslationObserver;
use App\Observers\ExportFileObserver;
use App\Policies\ExportFilePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Support\GuestContext;
use App\Support\ImageOptimizer;
use App\Support\PermissionTeam;
use App\Support\RestaurantTheme;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\ComponentHookRegistry;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ComponentHookRegistry::register(BlockGraceMutations::class);
    }

    public function boot(): void
    {
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        static::registerStyle();

        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ExportFile::class, ExportFilePolicy::class);
        ExportFile::observe(ExportFileObserver::class);
        BlogPost::observe(BlogPostObserver::class);
        BlogPostTranslation::observe(BlogPostTranslationObserver::class);
        BlogComment::observe(BlogCommentObserver::class);

        Gate::before(function ($user, string $ability, mixed $arguments = []): ?bool {
            if (! $user instanceof User) {
                return null;
            }

            $model = is_array($arguments) ? ($arguments[0] ?? null) : $arguments;

            if (
                $model instanceof Role
                && $model->isOwnerRole()
                && in_array($ability, ['update', 'delete', 'forceDelete', 'restore', 'replicate'], true)
            ) {
                return false;
            }

            if ($user->isPlatformOperator()) {
                return true;
            }

            PermissionTeam::syncFromTenant(user: $user);

            if (
                method_exists($user, 'checkPermissionTo')
                && ! in_array($ability, [
                    'viewAny',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'deleteAny',
                    'restore',
                    'forceDelete',
                    'forceDeleteAny',
                    'restoreAny',
                    'replicate',
                    'reorder',
                ], true)
            ) {
                return $user->checkPermissionTo($ability) ?: null;
            }

            return null;
        });

        Gate::define('command-center:access', fn($user): bool => $user instanceof User && $user->isPlatformOperator());
        Gate::define('command-center:prune-history', fn($user): bool => $user instanceof User && $user->isPlatformOperator());
        Gate::define('command-center:manage-commands', fn($user): bool => $user instanceof User && $user->isPlatformOperator());

        View::composer('layouts.guest-order', function ($view): void {
            $visit = GuestContext::visit();
            $restaurant = $visit?->outlet?->restaurant;

            if ($restaurant && ! $restaurant->relationLoaded('cmsProfile')) {
                $restaurant->load('cmsProfile');
            }

            $view->with('theme', RestaurantTheme::for($restaurant));
            $view->with('guestRestaurant', $restaurant);
            $view->with('guestVisit', $visit);
        });

        FileUpload::configureUsing(function (FileUpload $component): void {
            if ($component->getDiskName() === 'public') {
                $component->deleteUploadedFileUsing(
                    fn(string $file): bool => Storage::disk('public')->delete($file),
                );
            }

            $component->validationMessages([
                'max' => 'Ukuran berkas terlalu besar (maksimal :max KB).',
                'mimes' => 'Format berkas tidak didukung. Harap gunakan format: :values.',
                'image' => 'Berkas harus berupa gambar yang valid (JPG, PNG, WEBP).',
                'dimensions' => 'Dimensi gambar tidak sesuai dengan ketentuan.',
            ]);

            $component->saveUploadedFileUsing(static function (FileUpload $component, TemporaryUploadedFile $file): ?string {
                $storedFile = $component->saveUploadedFile($file);

                if ($storedFile && in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) {
                    // Safety net server-side optimization:
                    // Otomatis pastikan resolusi dan ukuran gambar di disk publik tetap terkendali (maks. 1 MB).
                    ImageOptimizer::optimize(
                        path: $storedFile,
                        maxWidth: 1920,
                        maxHeight: 1080,
                        maxBytes: 1048576,
                        disk: $component->getDiskName()
                    );
                }

                return $storedFile;
            });
        });
    }

    private static function registerStyle(): void
    {
        CreateAction::configureUsing(function (CreateAction $action): void {
            $action
                ->label('Tambah Data')
                ->color('primary')
                ->icon(Heroicon::PlusCircle)
                ->modalWidth(Width::Large)
                ->modalIcon(Heroicon::PlusCircle)
                ->modalHeading('Tambah Data')
                ->successNotificationTitle("Data telah berhasil disimpan")
                ->failureNotificationTitle("Terjadi kesalahan saat menyimpan data");
        });

        EditAction::configureUsing(function (EditAction $action): void {
            $action
                ->color('warning')
                ->icon(Heroicon::PencilSquare)
                ->modalWidth(Width::Large)
                ->modalIcon(Heroicon::PencilSquare)
                ->modalHeading('Edit Data')
                ->successNotificationTitle("Data telah berhasil diperbarui")
                ->failureNotificationTitle("Terjadi kesalahan saat memperbarui data");
        });

        ViewAction::configureUsing(function (ViewAction $action): void {
            $action
                ->color('gray')
                ->icon(Heroicon::Eye)
                ->modalWidth(Width::Large)
                ->modalIcon(Heroicon::Eye)
                ->modalHeading('Detail Data');
        });

        DeleteAction::configureUsing(function (DeleteAction $action): void {
            $action
                ->color('danger')
                ->icon(Heroicon::Trash)
                ->requiresConfirmation()
                ->modalHeading('KONFIRMASI')
                ->modalWidth(Width::Medium)
                ->modalDescription("konfirmasi untuk menghapus data")
                ->successNotificationTitle("Data telah berhasil dihapus")
                ->failureNotificationTitle("Terjadi kesalahan saat menghapus data")
            ;
        });

        RestoreAction::configureUsing(function (RestoreAction $action): void {
            $action
                ->color('gray')
                ->icon(Heroicon::ArrowPath)
                ->requiresConfirmation()
                ->modalHeading('KONFIRMASI')
                ->modalWidth(Width::Medium)
                ->modalDescription("konfirmasi untuk memulihkan data")
                ->successNotificationTitle("Data telah berhasil dipulihkan")
                ->failureNotificationTitle("Terjadi kesalahan saat memulihkan data")
            ;
        });

        DeleteBulkAction::configureUsing(function (DeleteBulkAction $action): void {
            $action
                ->color('danger')
                ->icon(Heroicon::Trash)
                ->requiresConfirmation()
                ->modalHeading('KONFIRMASI')
                ->modalWidth(Width::Medium)
                ->modalDescription('konfirmasi untuk menghapus data yang dipilih')
                ->successNotificationTitle("Data yang dipilih telah berhasil dihapus")
                ->failureNotificationTitle("Terjadi kesalahan saat menghapus data yang dipilih")
            ;
        });

        RestoreBulkAction::configureUsing(function (RestoreBulkAction $action): void {
            $action
                ->color('gray')
                ->icon(Heroicon::Trash)
                ->requiresConfirmation()
                ->modalHeading('KONFIRMASI')
                ->modalWidth(Width::Medium)
                ->modalDescription('konfirmasi untuk memulihkan data yang dipilih')
                ->successNotificationTitle("Data yang dipilih telah berhasil dipulihkan")
                ->failureNotificationTitle("Terjadi kesalahan saat memulihkan data yang dipilih")
            ;
        });

        ForceDeleteAction::configureUsing(function (ForceDeleteAction $action) {
            $action
                ->successNotificationTitle("Data telah berhasil dihapus permanen")
                ->failureNotificationTitle("Terjadi kesalahan saat menghapus data");
        });

        ForceDeleteBulkAction::configureUsing(function (ForceDeleteBulkAction $action): void {
            $action
                ->color('danger')
                ->icon(Heroicon::Trash)
                ->requiresConfirmation()
                ->modalHeading('KONFIRMASI')
                ->modalWidth(Width::Medium)
                ->modalDescription('konfirmasi untuk menghapus permanen data yang dipilih')
                ->successNotificationTitle("Data yang dipilih telah berhasil dihapus permanen")
                ->failureNotificationTitle("Terjadi kesalahan saat menghapus permanen data yang dipilih")
            ;
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->groupRecordsTriggerAction(
                    fn(Action $action) => $action
                        ->button()
                        ->color('primary')
                        ->label('Group'),
                )
                ->selectable()
                ->emptyStateHeading('TIDAK ADA DATA')
                ->emptyStateDescription('belum ada data ditambahkan')
                ->emptyStateIcon(HeroIcon::FolderOpen)
                ->filtersFormWidth('2xl')
                ->filtersFormColumns(3)
                ->defaultPaginationPageOption(6)
                ->extremePaginationLinks()
                ->striped()
                ->paginated([5, 10, 25, 50])
                ->paginationMode(PaginationMode::Default)
                ->headerActionsPosition(HeaderActionsPosition::Bottom)
                ->filtersLayout(FiltersLayout::Modal)
                ->filtersTriggerAction(
                    fn(Action $action) => $action
                        ->badgeColor('primary')
                        ->label('Filter'),
                )
                ->filtersApplyAction(
                    fn(Action $action) => $action
                        ->badge()
                        ->button()
                        ->color('primary')
                        ->label('Terapkan Filter')
                );
        });

        Select::configureUsing(function (Select $select): void {
            $select
                ->preload()
                ->placeholder('')
                ->searchable()
                ->native(false);
        });

        DatePicker::configureUsing(function (DatePicker $datePicker): void {
            $datePicker
                ->prefixIcon(Heroicon::Calendar)
                ->displayFormat('d M Y')
                ->native(false);
        });

        FileUpload::configureUsing(function (FileUpload $fileUpload) {
            $fileUpload
                ->openable()
                ->downloadable()
                ->alignCenter()
                ->panelLayout('integrated')
                ->loadingIndicatorPosition('center')
                ->removeUploadedFileButtonPosition('right')
                ->uploadButtonPosition('center')
                ->uploadProgressIndicatorPosition('center');
        });

        SelectFilter::configureUsing(function (SelectFilter $select) {
            $select
                ->preload()
                ->searchable()
                ->native(false);
        });
    }
}
