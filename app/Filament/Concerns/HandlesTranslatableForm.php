<?php

namespace App\Filament\Concerns;

use App\Support\Locales;
use Illuminate\Validation\ValidationException;

trait HandlesTranslatableForm
{
    /** @var array<string, array<string, mixed>> */
    protected array $translationsToSave = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! method_exists($this, 'getRecord')) {
            return $data;
        }

        $record = $this->getRecord();

        if (! method_exists($record, 'translate')) {
            return $data;
        }

        foreach (Locales::all() as $locale) {
            $translation = $record->translate($locale);
            $data[$locale] = $translation ? $translation->toArray() : [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->extractTranslations($data);
        $this->ensureAtLeastOneTranslation();

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->extractTranslations($data);
        $this->ensureAtLeastOneTranslation();

        return $data;
    }

    protected function ensureAtLeastOneTranslation(): void
    {
        $hasAny = false;
        foreach ($this->translationsToSave as $locale => $fields) {
            if ($this->hasTranslatableContent($fields)) {
                $hasAny = true;
                break;
            }
        }

        if (! $hasAny) {
            throw ValidationException::withMessages([
                'translations' => 'Harap isi konten untuk setidaknya satu bahasa.',
            ]);
        }
    }

    protected function afterSave(): void
    {
        $this->persistTranslations();
    }

    protected function afterCreate(): void
    {
        $this->persistTranslations();
    }

    private function extractTranslations(array $data): array
    {
        foreach (Locales::all() as $locale) {
            if (array_key_exists($locale, $data)) {
                $this->translationsToSave[$locale] = $data[$locale];
                unset($data[$locale]);
            }
        }

        return $data;
    }

    private function persistTranslations(): void
    {
        if ($this->translationsToSave === []) {
            return;
        }

        $translationsModified = false;

        foreach ($this->translationsToSave as $locale => $fields) {
            $hasContent = $this->hasTranslatableContent($fields);
            $existingTranslation = method_exists($this->record, 'translate')
                ? $this->record->translate($locale)
                : null;

            if (! $hasContent) {
                if ($existingTranslation) {
                    $existingTranslation->delete();
                    $translationsModified = true;
                }
                continue;
            }

            $this->record->translateOrNew($locale)->fill($fields);
            $translationsModified = true;
        }

        if ($translationsModified) {
            $this->record->save();
            if (method_exists($this->record, 'unsetRelation')) {
                $this->record->unsetRelation('translations');
            }
        }

        $this->translationsToSave = [];
    }

    /**
     * Determine if a set of translation fields contains meaningful content.
     *
     * @param  array<string, mixed>  $fields
     */
    protected function hasTranslatableContent(array $fields): bool
    {
        if (array_key_exists('title', $fields)) {
            return filled($fields['title']);
        }

        if (array_key_exists('name', $fields)) {
            return filled($fields['name']);
        }

        if (array_key_exists('badge_text', $fields)) {
            return filled($fields['badge_text']);
        }

        $ignoredKeys = ['robots', 'sort_order'];
        foreach ($fields as $key => $value) {
            if (in_array($key, $ignoredKeys, true)) {
                continue;
            }

            if (is_string($value)) {
                if (trim(strip_tags($value)) !== '') {
                    return true;
                }
            } elseif (filled($value)) {
                return true;
            }
        }

        return false;
    }
}
