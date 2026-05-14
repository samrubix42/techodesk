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
    public string $invoiceDate = '';
    public bool $confirmOpen = false;
    public string $copyInvoiceType = 'all';
    public string $copyInvoiceSearch = '';
    public bool $copyPreviewOpen = false;
    public ?array $copyPreviewInvoice = null;

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
    public ?int $proformaDueDays = null;
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
        $this->invoiceDate = Carbon::now()->format('Y-m-d');
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
            'invoice_proforma_due_days',
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
        $this->proformaDueDays = is_numeric($settings['invoice_proforma_due_days'] ?? null) ? (int) $settings['invoice_proforma_due_days'] : null;
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

    public function getCopySourceInvoicesProperty(): array
    {
        if (!$this->selectedClientId) {
            return [];
        }

        $sources = collect();

        if (in_array($this->copyInvoiceType, ['all', 'proforma'], true)) {
            $sources = $sources->merge(
                ProformaInvoice::with(['client', 'serviceAndPrices'])
                    ->where('client_id', $this->selectedClientId)
                    ->when($this->copyInvoiceSearch !== '', function ($query) {
                        $search = '%' . trim($this->copyInvoiceSearch) . '%';
                        $query->where(function ($inner) use ($search) {
                            $inner->where('invoice_number', 'like', $search)
                                ->orWhereHas('serviceAndPrices', fn ($items) => $items->where('service_details', 'like', $search));
                        });
                    })
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(fn (ProformaInvoice $invoice) => $this->formatCopySource($invoice, 'proforma'))
            );
        }

        if (in_array($this->copyInvoiceType, ['all', 'general'], true)) {
            $sources = $sources->merge(
                GeneralInvoice::with(['client', 'serviceAndPrices'])
                    ->where('client_id', $this->selectedClientId)
                    ->when($this->copyInvoiceSearch !== '', function ($query) {
                        $search = '%' . trim($this->copyInvoiceSearch) . '%';
                        $query->where(function ($inner) use ($search) {
                            $inner->where('invoice_number', 'like', $search)
                                ->orWhereHas('serviceAndPrices', fn ($items) => $items->where('service_details', 'like', $search));
                        });
                    })
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(fn (GeneralInvoice $invoice) => $this->formatCopySource($invoice, 'general'))
            );
        }

        return $sources
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->all();
    }

    public function selectClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->copyInvoiceSearch = '';
        $this->copyPreviewInvoice = null;
        $this->copyPreviewOpen = false;
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
            'invoiceDate' => ['required', 'date'],
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

    public function getPreviewDueDateProperty(): ?string
    {
        if ($this->invoiceType !== 'proforma' || $this->proformaDueDays === null || $this->invoiceDate === '') {
            return null;
        }

        return Carbon::parse($this->invoiceDate)->addDays($this->proformaDueDays)->format('d/m/Y');
    }

    public function addItem(): void
    {
        $this->invoiceItems[] = ['service_details' => '', 'price' => null];
    }

    public function previewCopyInvoice(string $type, int $invoiceId): void
    {
        $invoice = $this->findCopyInvoice($type, $invoiceId);

        if (!$invoice) {
            $this->dispatch('toast', message: 'Invoice not found for this client.', type: 'error');
            return;
        }

        $this->copyPreviewInvoice = $this->formatCopySource($invoice, $type, true);
        $this->copyPreviewOpen = true;
    }

    public function closeCopyPreview(): void
    {
        $this->copyPreviewOpen = false;
    }

    public function copyFromInvoice(string $type, int $invoiceId): void
    {
        $invoice = $this->findCopyInvoice($type, $invoiceId);

        if (!$invoice) {
            $this->dispatch('toast', message: 'Invoice not found for this client.', type: 'error');
            return;
        }

        $items = $invoice->serviceAndPrices
            ->map(fn (InvoiceServiceAndPrice $item) => [
                'service_details' => (string) $item->service_details,
                'price' => (float) $item->price,
            ])
            ->values()
            ->all();

        $this->selectedServiceId = $invoice->service_id;
        $this->status = $invoice->status ?: 'unpaid';
        $this->invoiceItems = $items !== [] ? $items : [['service_details' => '', 'price' => null]];
        $this->copyPreviewOpen = false;

        $this->dispatch('toast', message: 'Invoice details copied. Client, date, and new invoice number were kept.', type: 'success');
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
                'invoice_date' => $this->invoiceDate,
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
        $this->invoiceDate = Carbon::now()->format('Y-m-d');
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

    private function findCopyInvoice(string $type, int $invoiceId): GeneralInvoice|ProformaInvoice|null
    {
        if (!in_array($type, ['proforma', 'general'], true) || !$this->selectedClientId) {
            return null;
        }

        $model = $type === 'general' ? GeneralInvoice::class : ProformaInvoice::class;

        return $model::with(['client', 'serviceAndPrices'])
            ->where('client_id', $this->selectedClientId)
            ->find($invoiceId);
    }

    private function formatCopySource(GeneralInvoice|ProformaInvoice $invoice, string $type, bool $withItems = false): array
    {
        $items = $invoice->serviceAndPrices
            ->map(fn (InvoiceServiceAndPrice $item) => [
                'service_details' => (string) $item->service_details,
                'price' => (float) $item->price,
            ])
            ->values()
            ->all();

        return [
            'id' => $invoice->id,
            'type' => $type,
            'type_label' => $type === 'general' ? 'Tax Invoice' : 'Proforma Invoice',
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date?->format('d/m/Y') ?? $invoice->created_at?->format('d/m/Y') ?? '-',
            'client_name' => $invoice->client?->name ?: '-',
            'status' => strtoupper($invoice->status ?: '-'),
            'total_price' => (float) $invoice->total_price,
            'created_at' => $invoice->created_at?->timestamp ?? 0,
            'item_count' => count($items),
            'items' => $withItems ? $items : [],
        ];
    }

    private function toNullableFloat(null|string $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
};
