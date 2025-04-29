<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DispatchNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $dispatchRequest;

    public function __construct(DispatchRequest $dispatchRequest)
    {
        $this->dispatchRequest = $dispatchRequest;
    }

    public function build()
    {
        return $this->subject('Dispatch Request Approved')
        ->view('emails.dispatch_note');
    }

}
