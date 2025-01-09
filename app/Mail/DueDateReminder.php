<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DueDateReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    /**
     * Create a new message instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Könyv visszavételi határideje közeleg')
            ->markdown('emails.due_date_reminder');
    }
}
