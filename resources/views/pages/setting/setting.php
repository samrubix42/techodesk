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
    public ?string $accountHolderName = null;
    public ?string $accountNumber = null;
    public ?string $ifscCode = null;
    public ?string $branch = null;
    public ?string $upiId = null;
    public ?string $companyAddress = null;
    public ?string $companyState = null;
    public ?string $companyCountry = null;
    public ?float $igst = null;
    public ?float $cgst = null;
    public ?float $sgst = null;
    public ?string $proformaNotes = null;
    public ?string $generalNotes = null;

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
            'bank_account_holder_name',
            'bank_account_number',
            'bank_ifsc_code',
            'bank_branch',
            'bank_upi_id',
            'company_address',
            'company_state',
            'company_country',
            'tax_igst',
            'tax_cgst',
            'tax_sgst',
            'invoice_proforma_notes',
            'invoice_general_notes',
        ])->pluck('value', 'key');

        $this->headerImagePath = $settings['invoice_header_image_path'] ?? null;
        $this->headerWidth = $this->toNullableFloat($settings['invoice_header_image_width'] ?? null);
        $this->headerHeight = $this->toNullableFloat($settings['invoice_header_image_height'] ?? null);

        $this->footerImagePath = $settings['invoice_footer_image_path'] ?? null;
        $this->footerWidth = $this->toNullableFloat($settings['invoice_footer_image_width'] ?? null);
        $this->footerHeight = $this->toNullableFloat($settings['invoice_footer_image_height'] ?? null);

        $this->accountHolderName = $settings['bank_account_holder_name'] ?? null;
        $this->accountNumber = $settings['bank_account_number'] ?? null;
        $this->ifscCode = $settings['bank_ifsc_code'] ?? null;
        $this->branch = $settings['bank_branch'] ?? null;
        $this->upiId = $settings['bank_upi_id'] ?? null;
        $this->companyAddress = $settings['company_address'] ?? null;
        $this->companyState = $settings['company_state'] ?? null;
        $this->companyCountry = $settings['company_country'] ?? null;
        $this->igst = $this->toNullableFloat($settings['tax_igst'] ?? null);
        $this->cgst = $this->toNullableFloat($settings['tax_cgst'] ?? null);
        $this->sgst = $this->toNullableFloat($settings['tax_sgst'] ?? null);
        $this->proformaNotes = $settings['invoice_proforma_notes'] ?? '';
        $this->generalNotes = $settings['invoice_general_notes'] ?? '';
    }

    public function getCountriesProperty()
    {
        return \App\Models\Country::all();
    }

    public function getStatesProperty()
    {
        if ($this->companyCountry === 'India') {
            return \App\Models\State::all();
        }
        return collect();
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

    public function saveBankDetails(): void
    {
        $data = $this->validate([
            'accountHolderName' => ['nullable', 'string', 'max:255'],
            'accountNumber' => ['nullable', 'string', 'max:255'],
            'ifscCode' => ['nullable', 'string', 'max:255'],
            'branch' => ['nullable', 'string', 'max:255'],
            'upiId' => ['nullable', 'string', 'max:255'],
            'companyAddress' => ['nullable', 'string', 'max:1000'],
            'companyState' => ['nullable', 'string', 'max:255'],
            'companyCountry' => ['nullable', 'string', 'max:255'],
            'igst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $this->putSetting('bank_account_holder_name', $this->nullableTrim($data['accountHolderName'] ?? null));
        $this->putSetting('bank_account_number', $this->nullableTrim($data['accountNumber'] ?? null));
        $this->putSetting('bank_ifsc_code', $this->nullableTrim($data['ifscCode'] ?? null));
        $this->putSetting('bank_branch', $this->nullableTrim($data['branch'] ?? null));
        $this->putSetting('bank_upi_id', $this->nullableTrim($data['upiId'] ?? null));
        $this->putSetting('company_address', $this->nullableTrim($data['companyAddress'] ?? null));
        $this->putSetting('company_state', $this->nullableTrim($data['companyState'] ?? null));
        $this->putSetting('company_country', $this->nullableTrim($data['companyCountry'] ?? null));
        $this->putSetting('tax_igst', $this->valueOrNull($this->igst));
        $this->putSetting('tax_cgst', $this->valueOrNull($this->cgst));
        $this->putSetting('tax_sgst', $this->valueOrNull($this->sgst));

        $this->dispatch('toast', message: 'Bank details updated', type: 'success');
    }

    public function saveNotes(): void
    {
        $data = $this->validate([
            'proformaNotes' => ['nullable', 'string'],
            'generalNotes' => ['nullable', 'string'],
        ]);

        $this->putSetting('invoice_proforma_notes', $this->nullableTrim($data['proformaNotes'] ?? null));
        $this->putSetting('invoice_general_notes', $this->nullableTrim($data['generalNotes'] ?? null));

        $this->dispatch('toast', message: 'Invoice notes updated', type: 'success');
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

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
};
