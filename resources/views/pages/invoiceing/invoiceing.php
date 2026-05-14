<?php

use App\Models\Client;
use App\Models\GeneralInvoice;
use App\Models\InvoiceServiceAndPrice;
use App\Models\ProformaInvoice;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component
{
    public int $step = 1;
    public string $invoiceType = 'proforma';
    public bool $invoiceTypeLocked = false;
    public string $clientSearch = '';
    public ?int $selectedClientId = null;
    public ?int $selectedServiceId = null;
    public array $invoiceItems = [];
    public string $status = 'unpaid';
    public bool $confirmOpen = false;

    public ?string $headerImagePath = null;
    public ?float $headerWidth = null;
    public ?float $headerHeight = null;
    public ?string $footerImagePath = null;
    public ?float $footerWidth = null;
    public ?float $footerHeight = null;

    public ?string $accountHolderName = null;
    public ?string $accountNumber = null;
    public ?string $ifscCode = null;
    public ?string $branch = null;
    public ?string $upiId = null;
    public ?float $igst = null;
    public ?float $cgst = null;
    public ?float $sgst = null;
    public ?string $proformaNotes = null;
    public ?string $generalNotes = null;
    public ?string $companyAddress = null;
    public ?string $companyState = null;
    public ?string $companyCountry = null;

    public function mount(?string $type = null): void
    {
        if (in_array($type, ['proforma', 'general'], true)) {
            $this->invoiceType = $type;
            $this->invoiceTypeLocked = true;
        }

        $this->invoiceItems = [
            ['service_details' => '', 'price' => null],
        ];
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
            'tax_igst',
            'tax_cgst',
            'tax_sgst',
            'invoice_proforma_notes',
            'invoice_general_notes',
            'company_address',
            'company_state',
            'company_country',
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

        $this->igst = $this->toNullableFloat($settings['tax_igst'] ?? null);
        $this->cgst = $this->toNullableFloat($settings['tax_cgst'] ?? null);
        $this->sgst = $this->toNullableFloat($settings['tax_sgst'] ?? null);
        $this->proformaNotes = $settings['invoice_proforma_notes'] ?? '';
        $this->generalNotes = $settings['invoice_general_notes'] ?? '';
        $this->companyAddress = $settings['company_address'] ?? null;
        $this->companyState = $settings['company_state'] ?? null;
        $this->companyCountry = $settings['company_country'] ?? null;
    }

    public function getClientsProperty()
    {
        return Client::query()
            ->when($this->clientSearch !== '', function ($query) {
                $search = '%' . trim($this->clientSearch) . '%';
                $query->where('name', 'like', $search)
                    ->orWhere('business_name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('phone', 'like', $search);
            })
            ->orderBy('name')
            ->limit(15)
            ->get();
    }

    public function getSelectedClientProperty(): ?Client
    {
        if (!$this->selectedClientId) {
            return null;
        }

        return Client::find($this->selectedClientId);
    }

    public function getServicesProperty()
    {
        return Service::orderBy('name')->get();
    }

    public function selectClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
    }

    public function nextStep(): void
    {
        if ($this->invoiceTypeLocked) {
            $this->invoiceType = in_array($this->invoiceType, ['proforma', 'general'], true) ? $this->invoiceType : 'proforma';
        }

        $this->validate([
            'invoiceType' => ['required', 'in:proforma,general'],
            'selectedClientId' => ['required', 'exists:clients,id'],
        ]);

        $this->step = 2;
    }

    public function backStep(): void
    {
        $this->step = 1;
    }

    public function openConfirm(): void
    {
        $this->validate([
            'invoiceType' => ['required', 'in:proforma,general'],
            'selectedClientId' => ['required', 'exists:clients,id'],
            'invoiceItems' => ['required', 'array', 'min:1'],
            'invoiceItems.*.service_details' => ['required', 'string'],
            'invoiceItems.*.price' => ['required', 'numeric', 'min:0.01'],
            'selectedServiceId' => ['nullable', 'exists:services,id'],
            'status' => ['required', 'in:unpaid,paid'],
        ]);

        $this->confirmOpen = true;
    }

    public function closeConfirm(): void
    {
        $this->confirmOpen = false;
    }

    public function getTaxPercentProperty(): float
    {
        $igst = $this->igst ?? 0;
        if ($igst > 0) {
            return $igst;
        }

        return ($this->cgst ?? 0) + ($this->sgst ?? 0);
    }

    public function getTaxLabelProperty(): string
    {
        $igst = $this->igst ?? 0;
        if ($igst > 0) {
            return 'IGST (' . number_format($igst, 2) . '%)';
        }

        return 'GST (' . number_format($this->taxPercent, 2) . '%)';
    }

    public function getTaxAmountProperty(): float
    {
        return round($this->subTotal * ($this->taxPercent / 100), 2);
    }

    public function getTotalAmountProperty(): float
    {
        return round($this->subTotal + $this->taxAmount, 2);
    }

    public function getSubTotalProperty(): float
    {
        return round(collect($this->invoiceItems)->sum(function (array $item) {
            return (float) ($item['price'] ?? 0);
        }), 2);
    }

    public function getPreviewInvoiceNumberProperty(): string
    {
        return $this->generateInvoiceNumber($this->invoiceType);
    }

    public function getCurrentNotesProperty(): string
    {
        return $this->invoiceType === 'proforma'
            ? (string) $this->proformaNotes
            : (string) $this->generalNotes;
    }

    public function addItem(): void
    {
        $this->invoiceItems[] = ['service_details' => '', 'price' => null];
    }

    public function removeItem(int $index): void
    {
        if (count($this->invoiceItems) === 1) {
            return;
        }

        unset($this->invoiceItems[$index]);
        $this->invoiceItems = array_values($this->invoiceItems);
    }

    public function saveInvoice(): void
    {
        $this->persistInvoice(false);
    }

    public function saveInvoiceAndDownload()
    {
        return $this->persistInvoice(true);
    }

    private function persistInvoice(bool $downloadPdf)
    {
        $this->openConfirm();

        $isGeneral = $this->invoiceType === 'general';
        $invoiceNumber = $this->generateUniqueInvoiceNumber($this->invoiceType);
        $createdInvoiceId = null;

        DB::transaction(function () use ($isGeneral, $invoiceNumber, &$createdInvoiceId) {
            $invoiceData = [
                'client_id' => $this->selectedClientId,
                'service_id' => $this->selectedServiceId,
                'invoice_number' => $invoiceNumber,
                'status' => $this->status,
                'total_price' => $this->totalAmount,
            ];

            $invoice = $isGeneral
                ? GeneralInvoice::create($invoiceData)
                : ProformaInvoice::create($invoiceData);
            $createdInvoiceId = $invoice->id;

            foreach ($this->invoiceItems as $item) {
                InvoiceServiceAndPrice::create([
                    'invoice_id' => $invoice->id,
                    'service_details' => $item['service_details'],
                    'invoice_number' => $invoiceNumber,
                    'price' => (float) $item['price'],
                    'is_general_invoice' => $isGeneral,
                ]);
            }
        });

        $this->confirmOpen = false;
        $this->invoiceItems = [['service_details' => '', 'price' => null]];
        $this->selectedServiceId = null;
        $this->step = 1;

        $this->dispatch('toast', message: 'Invoice created: ' . $invoiceNumber, type: 'success');

        if ($downloadPdf && $createdInvoiceId) {
            return redirect()->route('invoice.pdf', [
                'type' => $isGeneral ? 'general' : 'proforma',
                'invoice' => $createdInvoiceId,
            ]);
        }

        return null;
    }

    private function generateUniqueInvoiceNumber(string $type): string
    {
        return $this->generateInvoiceNumber($type);
    }

    private function generateInvoiceNumber(string $type): string
    {
        $prefix = $type === 'general' ? 'GV' : 'PI';
        $dateSegment = Carbon::now()->format('mdY');
        $base = $prefix . $dateSegment;

        $table = $type === 'general'
            ? (new GeneralInvoice())->getTable()
            : (new ProformaInvoice())->getTable();

        $lastNumber = DB::table($table)
            ->where('invoice_number', 'like', $base . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $next = 1;
        if (is_string($lastNumber)) {
            $lastPart = str_replace($base, '', $lastNumber);
            if (ctype_digit($lastPart)) {
                $next = ((int) $lastPart) + 1;
            }
        }

        return $base . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function toNullableFloat(null|string $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
};
