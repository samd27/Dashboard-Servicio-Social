<?php

namespace App\Mail;

use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment; // Importante para los PDFs
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmacionRegistroMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Datos que pasaremos a la vista del correo.
     */
    public $user;
    public $cuenta;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Cuenta $cuenta)
    {
        $this->user = $user;
        $this->cuenta = $cuenta;
    }

    /**
     * Get the message envelope (Asunto del correo).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido al Dashboard - Registro Exitoso',
        );
    }

    /**
     * Get the message content definition (La vista HTML).
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registro.confirmacion',
        );
    }

    /**
     * Get the attachments for the message (Los archivos adjuntos).
     */
    public function attachments(): array
    {
        return [
            // RF06: Adjuntar PDF 1
            Attachment::fromPath(storage_path('app/manuales/manual_panel.pdf'))
                ->as('Manual_Panel.pdf')
                ->withMime('application/pdf'),

            // RF06: Adjuntar PDF 2
            Attachment::fromPath(storage_path('app/manuales/guia_api.pdf'))
                ->as('Guia_Tecnica_API.pdf')
                ->withMime('application/pdf'),
        ];
    }
}