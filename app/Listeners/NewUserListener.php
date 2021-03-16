<?php

namespace App\Listeners;

use App\Events\NewUserEvent;
use App\Mail\AccountVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserEvent  $event
     * @return void
     */
    public function handle(NewUserEvent $event)
    {
        // send mail to new user
        Mail::to($event->user->email)
            ->send(new AccountVerificationMail($event->user));

    }
}
