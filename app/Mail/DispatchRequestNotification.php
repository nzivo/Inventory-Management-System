<?php

namespace App\Mail;

use App\Models\DispatchRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DispatchRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $dispatchRequest;

    /**
     * Create a new message instance.
     *
     * @param  DispatchRequest  $dispatchRequest
     */
    public function __construct(DispatchRequest $dispatchRequest)
    {
        $this->dispatchRequest = $dispatchRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Dispatch Request: ' . $this->dispatchRequest->dispatch_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dispatch_request_notification', // You'll need to create this view
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
