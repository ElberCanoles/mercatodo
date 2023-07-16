<?php

declare(strict_types=1);

namespace App\Domain\Imports\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImportSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public string $showImportsUrl;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->showImportsUrl = route(name: 'admin.imports.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Import Success',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.imports.success',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
