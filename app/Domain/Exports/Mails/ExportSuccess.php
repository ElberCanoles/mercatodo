<?php

declare(strict_types=1);

namespace App\Domain\Exports\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExportSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public string $showExportsUrl;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->showExportsUrl = route(name: 'admin.exports.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Export Success',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.exports.success',
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
