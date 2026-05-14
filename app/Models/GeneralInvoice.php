<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'status',
        'total_price',
    ];

    public function serviceAndPrices(): HasMany
    {
        return $this->hasMany(InvoiceServiceAndPrice::class, 'invoice_number', 'invoice_number')
            ->where('is_general_invoice', true);
    }
}
