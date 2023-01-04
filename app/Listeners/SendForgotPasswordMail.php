<?php

namespace App\Listeners;

use App\Events\ForgotPassword;
use App\Mail\ForgetPasswordEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordMail
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
     * @param  \App\Events\ForgotPassword  $event
     * @return void
     */
    public function handle(ForgotPassword $event)
    {
        Mail::to($event->user)->send(new ForgetPasswordEmail($event->user, $event->token));
    }
}
