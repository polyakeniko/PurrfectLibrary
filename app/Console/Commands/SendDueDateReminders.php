<?php

namespace App\Console\Commands;

use App\Mail\DueDateReminder;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDueDateReminders extends Command
{
    protected $signature = 'reminders:send-due-date';
    protected $description = 'Send due date reminders for loans';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $loans = Loan::where('return_due_date', '<=', now()->addDays(3))->get();
        foreach ($loans as $loan) {
            Mail::to($loan->user->email)->send(new DueDateReminder($loan));
        }

        $this->info('Due date reminders sent successfully.');
    }
}
