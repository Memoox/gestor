<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class DocumentoTurnado extends Mailable
{
    use Queueable, SerializesModels;

    //public $ruta_pdf;
    public $no_doc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($no_doc)
    {
        $this->no_doc = $no_doc;
       // $this->ruta_pdf = $ruta_pdf;
        //$this->ext = explode("." , $this->ruta_pdf);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Documento Turnado',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.documento_turnado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            
            //Attachment::fromStorage($this->ruta_pdf)->as('DocumentoTurnado.'.$this->ext[1]),
        ];
    }
}
