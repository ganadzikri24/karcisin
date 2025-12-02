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

    // Konstruktor: Menerima Data Transaksi & Isi File PDF
    public function __construct($transaction, $pdfContent)
    {
        $this->transaction = $transaction;
        $this->pdfContent = $pdfContent;
    }

    // Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Karcis.in - ' . $this->transaction->event->name,
        );
    }

    // Isi Email (View HTML)
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_approved',
        );
    }

    // Lampiran (File PDF)
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'E-Ticket-' . $this->transaction->customer_name . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}