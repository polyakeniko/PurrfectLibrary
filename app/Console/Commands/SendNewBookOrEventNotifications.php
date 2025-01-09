<?php

namespace App\Console\Commands;

use App\Mail\NewBookOrEventNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewBookOrEventNotifications extends Command
{
    protected $signature = 'notifications:send-new-book-or-event {message}';
    protected $description = 'Send notifications about new books or events';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $message = $this->argument('message');
        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewBookOrEventNotification($message));
        }

        $this->info('New book or event notifications sent successfully.');
    }
}
