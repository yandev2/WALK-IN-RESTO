<?php

namespace App\Filament\Profile;

use Filament\Actions\Action;
use Filament\Panel;
use Ipatco\FilamentProfile\FilamentProfilePlugin as BaseFilamentProfilePlugin;
use Ipatco\FilamentProfile\Widgets\AccountWidget;

class FilamentProfilePlugin extends BaseFilamentProfilePlugin
{
    public function register(Panel $panel): void
    {
        $panel->profile(
            $this->getProfilePage(),
            isSimple: $this->isSimpleProfile(),
        );

        if ($this->hasAccountWidget()) {
            $panel->widgets([
                AccountWidget::class,
            ]);
        }

        $panel->userMenuItems([
            'profile' => fn (Action $action): Action => $this->isShowingOnDropdown()
                ? $this->configureUserMenuAction($action)
                : $action->hidden(),
        ]);

        if ($this->isShowingOnSideNav()) {
            $panel->navigationItems([
                $this->getNavigationItem(),
            ]);
        }
    }
}
