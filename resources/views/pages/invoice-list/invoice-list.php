<?php

use App\Models\GeneralInvoice;
use App\Models\ProformaInvoice;
use Livewire\Component;

new class extends Component
{
    public string $search = '';
    public string $type = 'general';

    public function mount(?string $type = null): void
    {
        $this->type = request()->is('invoice-list/proforma') || $type === 'proforma'
            ? 'proforma'
            : 'general';
    }

    public function getInvoicesProperty()
    {
        $model = $this->type === 'proforma' ? ProformaInvoice::class : GeneralInvoice::class;

        return $model::with('client')
            ->when($this->search !== '', function ($query) {
                $query->where('invoice_number', 'like', '%' . trim($this->search) . '%')
                    ->orWhereHas('client', function ($clientQuery) {
                        $clientQuery->where('name', 'like', '%' . trim($this->search) . '%');
                    });
            })
            ->latest()
            ->limit(50)
            ->get();
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
};
