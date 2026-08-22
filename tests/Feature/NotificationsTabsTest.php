<?php

namespace Tests\Feature;

use Filament\Facades\Filament;
use Tests\TestCase;
use Zvizvi\FilamentNotificationsTabs\Livewire\DatabaseNotifications;

class NotificationsTabsTest extends TestCase
{
    public function test_admin_panel_uses_notification_tabs_component(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertTrue($panel->hasDatabaseNotifications());
        $this->assertTrue($panel->hasPlugin('filament-notifications-tabs'));
        $this->assertSame(DatabaseNotifications::class, $panel->getDatabaseNotificationsLivewireComponent());
    }

    public function test_founder_panel_uses_notification_tabs_component(): void
    {
        $panel = Filament::getPanel('founder');

        $this->assertTrue($panel->hasDatabaseNotifications());
        $this->assertTrue($panel->hasPlugin('filament-notifications-tabs'));
        $this->assertSame(DatabaseNotifications::class, $panel->getDatabaseNotificationsLivewireComponent());
    }
}
