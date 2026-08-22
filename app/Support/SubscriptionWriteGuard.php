<?php

namespace App\Support;

use App\Filament\Pages\SubscriptionStatus;
use App\Filament\Profile\EditProfile;

final class SubscriptionWriteGuard
{
    /**
     * @var list<string>
     */
    private const READ_ONLY_METHODS = [
        '$set',
        '$refresh',
        '$commit',
        '__dispatch',
        '__lazyLoad',
        '__lazyLoadIsland',
        'gotoPage',
        'nextPage',
        'previousPage',
        'setPage',
        'resetPage',
        'sortTable',
        'search',
        'setTableSearch',
        'resetTable',
        'resetTableFiltersForm',
        'applyTableFilters',
        'removeTableFilter',
        'removeTableFilters',
        'toggleTableColumnVisibility',
        'reorderTableColumns',
        'loadTable',
        'nextTablePage',
        'previousTablePage',
        'mountAction',
        'mountTableAction',
        'unmountAction',
        'unmountTableAction',
        'resetAction',
        'mountInteractsWithTable',
        'pollAlerts',
        'updatedActiveTab',
        'setActiveTab',
        'markAsRead',
        'markAllAsRead',
        'clearNotifications',
        'openDatabaseNotifications',
        'closeDatabaseNotifications',
        'markNotificationAsRead',
        'markAllNotificationsAsRead',
    ];

    public static function shouldBlockCall(object|string $component, string $method): bool
    {
        if (! SubscriptionAccess::isReadOnly()) {
            return false;
        }

        if (self::isWriteExemptComponent($component)) {
            return false;
        }

        return ! self::isReadOnlyMethod($method);
    }

    public static function isWriteExemptComponent(object|string $component): bool
    {
        $class = is_object($component) ? $component::class : $component;

        if (is_a($class, SubscriptionStatus::class, true) || is_a($class, EditProfile::class, true)) {
            return true;
        }

        return str_contains($class, 'DatabaseNotifications')
            || str_contains($class, 'Livewire\\Notifications')
            || str_contains($class, 'Filament\\Livewire\\Notifications');
    }

    public static function isReadOnlyMethod(string $method): bool
    {
        $method = ltrim($method, '\\');

        if ($method === '' || str_starts_with($method, '__') || str_starts_with($method, 'updated') || str_starts_with($method, 'updating')) {
            return true;
        }

        return in_array($method, self::READ_ONLY_METHODS, true);
    }
}
