<?php

namespace App\Filament\Blogger\Concerns;

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
                'translations' => 'Mohon isi konten untuk setidaknya satu bahasa (Indonesia atau Inggris).',
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

    /**
     * Intercept and humanize Livewire temporary file upload errors for blogger forms.
     */
    public function _uploadErrored($name, $errorsInJson, $isMultiple): void
    {
        $this->dispatch('upload:errored', name: $name)->self();

        $friendlyAttribute = 'berkas';
        if (str_contains($name, 'featured_image')) {
            $friendlyAttribute = 'gambar utama artikel';
        } elseif (str_contains($name, 'og_image')) {
            $friendlyAttribute = 'gambar open graph (medsos)';
        } elseif (str_contains($name, 'content')) {
            $friendlyAttribute = 'lampiran konten artikel';
        }

        $friendlyMessage = "Gagal mengunggah {$friendlyAttribute}. Pastikan berkas berformat JPG, PNG, atau WEBP dan ukuran maksimal 15 MB.";

        if (! is_null($errorsInJson)) {
            $decoded = json_decode($errorsInJson, true);
            $errors = $decoded['errors'] ?? null;

            if (is_array($errors) && ! empty($errors)) {
                $rawMsg = (string) (reset($errors)[0] ?? '');
                if (stripos($rawMsg, 'kilobita') !== false || stripos($rawMsg, 'terlalu besar') !== false || stripos($rawMsg, 'max') !== false || stripos($rawMsg, 'greater than') !== false) {
                    $friendlyMessage = "Ukuran {$friendlyAttribute} terlalu besar. Maksimal ukuran berkas adalah 15 MB.";
                } elseif (stripos($rawMsg, 'mimes') !== false || stripos($rawMsg, 'format') !== false) {
                    $friendlyMessage = "Format berkas {$friendlyAttribute} tidak didukung. Harap gunakan format JPG, PNG, atau WEBP.";
                }
            }
        }

        throw ValidationException::withMessages([$name => $friendlyMessage]);
    }
}

