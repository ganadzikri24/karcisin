<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class TicketApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $pdfContent;

    public function __construct($transaction, $pdfContent)
    {
        $this->transaction = $transaction;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Karcis.in - ' . $this->transaction->event->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_approved',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'E-Ticket-' . $this->transaction->customer_name . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}