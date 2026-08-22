<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Modifier;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MenuModifierService
{
    /**
     * @param  list<int|string>  $modifierIds
     * @return Collection<int, Modifier>
     */
    public function resolve(MenuItem $item, array $modifierIds = []): Collection
    {
        $item->loadMissing(['modifierGroups.modifiers']);
        $groups = $item->modifierGroups;
        $ids = collect($modifierIds)->map(fn ($id): int => (int) $id)->unique()->values();

        if ($groups->isEmpty()) {
            if ($ids->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'modifiers' => 'Menu ini tidak punya extra.',
                ]);
            }

            return collect();
        }

        $allowed = $groups
            ->flatMap(fn ($group) => $group->modifiers)
            ->where('is_active', true)
            ->keyBy('id');

        $selected = $ids->map(function (int $id) use ($allowed) {
            $modifier = $allowed->get($id);

            if (! $modifier instanceof Modifier) {
                throw ValidationException::withMessages([
                    'modifiers' => 'Pilihan extra tidak valid.',
                ]);
            }

            return $modifier;
        });

        foreach ($groups as $group) {
            $count = $selected->where('modifier_group_id', $group->id)->count();
            $min = $group->minRequired();
            $max = $group->maxAllowed();

            if ($count < $min) {
                throw ValidationException::withMessages([
                    'modifiers' => "Pilih extra untuk {$group->name}.",
                ]);
            }

            if ($count > $max) {
                throw ValidationException::withMessages([
                    'modifiers' => "Terlalu banyak pilihan di {$group->name}.",
                ]);
            }
        }

        return $selected->values();
    }
}
