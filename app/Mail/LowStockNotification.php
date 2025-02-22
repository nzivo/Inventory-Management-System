<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $type;

    /**
     * Create a new message instance.
     *
     * @param  Item  $item
     * @param  string  $type
     */
    public function __construct(Item $item, string $type)
    {
        $this->item = $item;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Customize the subject based on the notification type
        $subject = $this->getSubject();

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Render the correct view based on the type
        return new Content(
            view: 'emails.' . $this->type . '_notification',
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

    /**
     * Get the email subject based on the notification type.
     *
     * @return string
     */
    protected function getSubject()
    {
        switch ($this->type) {
            case 'license_renewal':
                return 'License Renewal Alert: ' . $this->item->name;
            case 'dispatch_request':
                return 'Dispatch Request: ' . $this->item->name;
            case 'stock_notification':
            default:
                return 'Low Stock Alert: ' . $this->item->name;
        }
    }
}
