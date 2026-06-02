<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAlert extends Model
{
    protected $fillable = [
        'name',
        'client_id',
        'service_id',
        'alert_type',
        'days_interval',
        'alert_date',
        'sent_at',
    ];

    protected $casts = [
        'alert_date' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
