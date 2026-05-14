<?php

use App\Models\ProformaInvoice;
use Livewire\Component;

new class extends Component
{
    public string $search = '';

    public function getProformaInvoicesProperty()
    {
        return ProformaInvoice::with('client')
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
        return 'Proforma Invoice List';
    }
};