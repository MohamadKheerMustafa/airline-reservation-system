<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketBookingMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $greeting;
    public $data;
    public $TicketInfo;
    public $footer;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $greeting, $data, $TicketInfo, $footer)
    {
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->data = $data;
        $this->TicketInfo = $TicketInfo;
        $this->footer = $footer;
    }
    public function build()
    {
        return $this->markdown('TicketBooking');
    }
}
