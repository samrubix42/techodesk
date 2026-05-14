<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralInvoice extends Model
{
    protected $fillable = [
        'client_id',
        'service_id',
        'invoice_number',
        'invoice_date',
        'status',
        'total_price',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceAndPrices(): HasMany
    {
        return $this->hasMany(InvoiceServiceAndPrice::class, 'invoice_id')
            ->where('is_general_invoice', true);
    }
}
