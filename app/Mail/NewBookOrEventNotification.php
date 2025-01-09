<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBookOrEventNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Új könyv vagy esemény a könyvtárban')
            ->markdown('emails.new_book_or_event_notification');
    }
}
