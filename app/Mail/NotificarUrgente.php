<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarUrgente extends Mailable
{
    use Queueable, SerializesModels;

    public $folio;
    
    public function __construct($folio)
    {
        $this->folio = $folio;
    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificar Urgente',
        );
    }

    
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion_urgente',
        );
    }

    
    public function attachments()
    {
        return [];
    }
}
