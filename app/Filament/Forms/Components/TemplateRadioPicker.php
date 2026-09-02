<?php

namespace App\Filament\Forms\Components;

use App\Models\LandingTemplate;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Collection;

class TemplateRadioPicker extends Field
{
    protected string $view = 'filament.forms.components.template-radio-picker';

    /**
     * @return Collection<int, LandingTemplate>
     */
    public function getTemplates(): Collection
    {
        return LandingTemplate::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
