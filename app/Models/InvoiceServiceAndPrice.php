<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceServiceAndPrice extends Model
{
    protected $fillable = [
        'invoice_id',
        'service_details',
        'invoice_number',
        'price',
        'is_general_invoice',
    ];

    protected $casts = [
        'is_general_invoice' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function generalInvoice(): BelongsTo
    {
        return $this->belongsTo(GeneralInvoice::class, 'invoice_number', 'invoice_number');
    }

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'invoice_number', 'invoice_number');
    }

    public function getInvoiceAttribute(): GeneralInvoice|ProformaInvoice|null
    {
        return $this->is_general_invoice
            ? $this->generalInvoice
            : $this->proformaInvoice;
    }
}
