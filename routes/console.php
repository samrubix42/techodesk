<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\ProjectAlert;
use App\Mail\PaymentAlertMail;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:send-payment-alerts', function () {
    $this->info('Scanning for pending project payment alerts...');

    $alerts = ProjectAlert::whereNull('sent_at')->get();
    $sentCount = 0;

    foreach ($alerts as $alert) {
        $shouldSend = false;

        if ($alert->alert_type === 'interval_days') {
            $alertDate = $alert->created_at->copy();
            $intervalDays = $alert->days_interval;
            if ($intervalDays && $alertDate->addDays($intervalDays)->isPast()) {
                $shouldSend = true;
            }
        } elseif ($alert->alert_type === 'specific_date') {
            if ($alert->alert_date && $alert->alert_date->isPast()) {
                $shouldSend = true;
            }
        }

        if ($shouldSend) {
            $clientName = $alert->client?->business_name ?? $alert->client?->name ?? 'Client';
            $serviceName = $alert->service?->name ?? 'Service';
            $this->info("Sending payment alert for Client: {$clientName}, Service: {$serviceName}");
            
            $toEmail = env('PAYMENT_ALERT_EMAIL', 'samcool3203@gmail.com');
            Mail::to($toEmail)->send(new PaymentAlertMail($alert));
            
            $alert->update(['sent_at' => now()]);
            $sentCount++;
        }
    }

    $this->info("Payment alert scan completed. Total alerts sent: {$sentCount}");
})->purpose('Scan for pending payment alerts and send emails');

Schedule::command('app:send-payment-alerts')->daily();
