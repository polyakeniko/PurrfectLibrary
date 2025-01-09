<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Reservation Status Changed')
            ->markdown('emails.reservation_status_changed');
    }
}
