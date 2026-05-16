<?php

use App\Models\GeneralInvoice;
use App\Models\ProformaInvoice;
use App\Models\Client;
use App\Models\Service;
use Livewire\Component;

new class extends Component
{
    public string $search = '';
    public string $type = 'general';
    public ?int $filterClient = null;
    public ?int $filterService = null;
    public ?string $filterDate = null;

    public function mount(?string $type = null): void
    {
        $this->type = request()->is('invoice-list/proforma') || $type === 'proforma'
            ? 'proforma'
            : 'general';
    }

    public function getInvoicesProperty()
    {
        $model = $this->type === 'proforma' ? ProformaInvoice::class : GeneralInvoice::class;

        return $model::with(['client', 'service'])
            ->when($this->search !== '', function ($query) {
                $search = '%' . trim($this->search) . '%';
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', $search)
                      ->orWhereHas('client', function ($cq) use ($search) {
                          $cq->where('name', 'like', $search)
                            ->orWhere('business_name', 'like', $search);
                      });
                });
            })
            ->when($this->filterClient, fn($q) => $q->where('client_id', $this->filterClient))
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->when($this->filterDate, fn($q) => $q->whereDate('invoice_date', $this->filterDate))
            ->latest()
            ->limit(100)
            ->get();
    }

    public function getClientsProperty()
    {
        return Client::orderBy('name')->get();
    }

    public function getServicesProperty()
    {
        return Service::orderBy('name')->get();
    }

    public function getPageTitleProperty(): string
    {
        return $this->type === 'proforma' ? 'Proforma Invoice List' : 'Tax Invoice List';
    }

    public function getTypeLabelProperty(): string
    {
        return $this->type === 'proforma' ? 'Proforma' : 'Tax';
    }

    public function getPdfTypeProperty(): string
    {
        return $this->type === 'proforma' ? 'proforma' : 'general';
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterClient', 'filterService', 'filterDate']);
    }
};
