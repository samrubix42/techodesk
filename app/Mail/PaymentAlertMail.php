<?php

namespace App\Mail;

use App\Models\ProjectAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectAlert $alert;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjectAlert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $clientName = $this->alert->client?->business_name ?? $this->alert->client?->name ?? 'Client';
        $serviceName = $this->alert->service?->name ?? 'Service';

        return new Envelope(
            subject: "Payment Alert: {$clientName} - {$serviceName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
