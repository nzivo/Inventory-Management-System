<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DeviceAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $serialNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Device Assignment Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.device_assignment',
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


    public function build()
    {
        $pdf = Pdf::loadView('pdf.delivery_note', ['serialNumber' => $this->serialNumber]);

        return $this->subject('Asset Delivery Note & Agreement')
                    ->markdown('emails.device_assignment')
                    ->attachData($pdf->output(), 'delivery_agreement_note.pdf', ['mime'=>'application/pdf',]);
    }
}
