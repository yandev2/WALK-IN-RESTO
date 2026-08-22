<?php

namespace App\Livewire\Hooks;

use App\Support\SubscriptionWriteGuard;
use Livewire\ComponentHook;

class BlockGraceMutations extends ComponentHook
{
    public function call($method, $params, $returnEarly, $metadata, $componentContext): void
    {
        if (! SubscriptionWriteGuard::shouldBlockCall($this->component, (string) $method)) {
            return;
        }

        abort(403, 'Masa langganan dalam mode lihat saja. Lunasi invoice untuk mengubah data.');
    }
}
