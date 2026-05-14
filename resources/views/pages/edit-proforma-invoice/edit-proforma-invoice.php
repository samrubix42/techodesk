<?php

use App\Models\Client;
use App\Models\InvoiceServiceAndPrice;
use App\Models\ProformaInvoice;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component
{
    public ProformaInvoice $invoice;
    public ?int $selectedClientId = null;
    public ?int $selectedServiceId = null;
    public string $invoiceDate = '';
    public string $status = 'unpaid';
    public array $invoiceItems = [];
    public ?float $igst = null;
    public ?float $cgst = null;
    public ?float $sgst = null;

    public function mount(ProformaInvoice $invoice): void
    {
        $this->invoice = $invoice->load('serviceAndPrices');
        $this->selectedClientId = $this->invoice->client_id;
        $this->selectedServiceId = $this->invoice->service_id;
        $this->invoiceDate = $this->invoice->invoice_date?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->status = $this->invoice->status;
        $this->invoiceItems = $this->invoice->serviceAndPrices
            ->map(fn (InvoiceServiceAndPrice $item) => [
                'service_details' => $item->service_details,
                'price' => (float) $item->price,
            ])
            ->values()
            ->all();

        if ($this->invoiceItems === []) {
            $this->invoiceItems = [['service_details' => '', 'price' => null]];
        }

        $settings = Setting::whereIn('key', ['tax_igst', 'tax_cgst', 'tax_sgst'])->pluck('value', 'key');
        $this->igst = $this->toNullableFloat($settings['tax_igst'] ?? null);
        $this->cgst = $this->toNullableFloat($settings['tax_cgst'] ?? null);
        $this->sgst = $this->toNullableFloat($settings['tax_sgst'] ?? null);
    }

    public function getClientsProperty()
    {
        return Client::orderBy('name')->get();
    }

    public function getServicesProperty()
    {
        return Service::orderBy('name')->get();
    }

    public function getTaxPercentProperty(): float
    {
        $igst = $this->igst ?? 0;

        return $igst > 0 ? $igst : (($this->cgst ?? 0) + ($this->sgst ?? 0));
    }

    public function getSubTotalProperty(): float
    {
        return round(collect($this->invoiceItems)->sum(fn (array $item) => (float) ($item['price'] ?? 0)), 2);
    }

    public function getTaxAmountProperty(): float
    {
        return round($this->subTotal * ($this->taxPercent / 100), 2);
    }

    public function getTotalAmountProperty(): float
    {
        return round($this->subTotal + $this->taxAmount, 2);
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

    public function save(): void
    {
        $this->validate([
            'selectedClientId' => ['required', 'exists:clients,id'],
            'selectedServiceId' => ['nullable', 'exists:services,id'],
            'invoiceDate' => ['required', 'date'],
            'status' => ['required', 'in:unpaid,paid'],
            'invoiceItems' => ['required', 'array', 'min:1'],
            'invoiceItems.*.service_details' => ['required', 'string'],
            'invoiceItems.*.price' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () {
            $this->invoice->update([
                'client_id' => $this->selectedClientId,
                'service_id' => $this->selectedServiceId,
                'invoice_date' => $this->invoiceDate,
                'status' => $this->status,
                'total_price' => $this->totalAmount,
            ]);

            $this->invoice->serviceAndPrices()->delete();

            foreach ($this->invoiceItems as $item) {
                InvoiceServiceAndPrice::create([
                    'invoice_id' => $this->invoice->id,
                    'service_details' => $item['service_details'],
                    'invoice_number' => $this->invoice->invoice_number,
                    'price' => (float) $item['price'],
                    'is_general_invoice' => false,
                ]);
            }
        });

        $this->invoice->refresh();
        $this->dispatch('toast', message: 'Proforma invoice updated.', type: 'success');
    }

    private function toNullableFloat(null|string $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
};
