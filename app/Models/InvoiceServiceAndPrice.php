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
        'price' => 'decimal:2',
        'is_general_invoice' => 'boolean',
    ];

    public function generalInvoice(): BelongsTo
    {
        return $this->belongsTo(GeneralInvoice::class, 'invoice_id');
    }

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'invoice_id');
    }

    public function getInvoiceAttribute(): GeneralInvoice|ProformaInvoice|null
    {
        return $this->is_general_invoice ? $this->generalInvoice : $this->proformaInvoice;
    }
}
