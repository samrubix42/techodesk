<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $headerImage;
    public $footerImage;

    public ?string $headerImagePath = null;
    public ?string $footerImagePath = null;

    public ?float $headerWidth = null;
    public ?float $headerHeight = null;
    public ?float $footerWidth = null;
    public ?float $footerHeight = null;
    public ?string $deleteTarget = null;

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        $settings = Setting::whereIn('key', [
            'invoice_header_image_path',
            'invoice_header_image_width',
            'invoice_header_image_height',
            'invoice_footer_image_path',
            'invoice_footer_image_width',
            'invoice_footer_image_height',
        ])->pluck('value', 'key');

        $this->headerImagePath = $settings['invoice_header_image_path'] ?? null;
        $this->headerWidth = $this->toNullableFloat($settings['invoice_header_image_width'] ?? null);
        $this->headerHeight = $this->toNullableFloat($settings['invoice_header_image_height'] ?? null);

        $this->footerImagePath = $settings['invoice_footer_image_path'] ?? null;
        $this->footerWidth = $this->toNullableFloat($settings['invoice_footer_image_width'] ?? null);
        $this->footerHeight = $this->toNullableFloat($settings['invoice_footer_image_height'] ?? null);
    }

    public function saveHeader(): void
    {
        $data = $this->validate([
            'headerImage' => ['nullable', 'image', 'max:2048'],
            'headerWidth' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'headerHeight' => ['nullable', 'numeric', 'min:1', 'max:100'],
        ]);

        if (!empty($data['headerImage'])) {
            $this->deleteStoredFile($this->headerImagePath);
            $path = $data['headerImage']->store('settings', 'public');
            $this->headerImagePath = $path;
            $this->putSetting('invoice_header_image_path', $path);
        }

        $this->putSetting('invoice_header_image_width', $this->valueOrNull($this->headerWidth));
        $this->putSetting('invoice_header_image_height', $this->valueOrNull($this->headerHeight));

        $this->reset('headerImage');
        $this->dispatch('toast', message: 'Header invoice updated', type: 'success');
    }

    public function saveFooter(): void
    {
        $data = $this->validate([
            'footerImage' => ['nullable', 'image', 'max:2048'],
            'footerWidth' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'footerHeight' => ['nullable', 'numeric', 'min:1', 'max:100'],
        ]);

        if (!empty($data['footerImage'])) {
            $this->deleteStoredFile($this->footerImagePath);
            $path = $data['footerImage']->store('settings', 'public');
            $this->footerImagePath = $path;
            $this->putSetting('invoice_footer_image_path', $path);
        }

        $this->putSetting('invoice_footer_image_width', $this->valueOrNull($this->footerWidth));
        $this->putSetting('invoice_footer_image_height', $this->valueOrNull($this->footerHeight));

        $this->reset('footerImage');
        $this->dispatch('toast', message: 'Footer invoice updated', type: 'success');
    }

    public function deleteHeader(): void
    {
        $this->deleteStoredFile($this->headerImagePath);

        $this->headerImagePath = null;
        $this->headerWidth = null;
        $this->headerHeight = null;

        $this->putSetting('invoice_header_image_path', null);
        $this->putSetting('invoice_header_image_width', null);
        $this->putSetting('invoice_header_image_height', null);

        $this->reset('headerImage');
        $this->dispatch('toast', message: 'Header invoice removed', type: 'success');
    }

    public function deleteFooter(): void
    {
        $this->deleteStoredFile($this->footerImagePath);

        $this->footerImagePath = null;
        $this->footerWidth = null;
        $this->footerHeight = null;

        $this->putSetting('invoice_footer_image_path', null);
        $this->putSetting('invoice_footer_image_width', null);
        $this->putSetting('invoice_footer_image_height', null);

        $this->reset('footerImage');
        $this->dispatch('toast', message: 'Footer invoice removed', type: 'success');
    }

    public function confirmDelete(string $target): void
    {
        $this->deleteTarget = $target;
    }

    public function deleteConfirmed(): void
    {
        if ($this->deleteTarget === 'header') {
            $this->deleteHeader();
        } elseif ($this->deleteTarget === 'footer') {
            $this->deleteFooter();
        }

        $this->deleteTarget = null;
        $this->dispatch('close-setting-delete-modal');
    }

    private function putSetting(string $key, ?string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function valueOrNull(?float $value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    private function toNullableFloat(?string $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
};