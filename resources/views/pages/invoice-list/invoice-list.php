<?php

use App\Models\GeneralInvoice;
use Livewire\Component;

new class extends Component
{
    public string $search = '';
    public function getGeneralInvoicesProperty()
    {
        return GeneralInvoice::with('client')
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
        return 'General Invoice List';
    }
};
