<?php

namespace App\Filament\Resources\Activities\Pages;

use App\Filament\Resources\Activities\ActivityResource;
use App\Support\ActivityPresenter;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return ActivityPresenter::eventLabel($record->event).' · '.Str::headline((string) $record->log_name);
    }
}
