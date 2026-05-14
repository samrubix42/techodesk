<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceServiceAndPrice extends Model
{
    protected $fillable = [
        'proforma_invoice_id',
        'general_invoice_id',
        'service_details',
        'invoice_number',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function generalInvoice(): BelongsTo
    {
        return $this->belongsTo(GeneralInvoice::class, 'general_invoice_id');
    }

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'proforma_invoice_id');
    }

    public function getInvoiceAttribute(): GeneralInvoice|ProformaInvoice|null
    {
        return $this->generalInvoice ?: $this->proformaInvoice;
    }
}
