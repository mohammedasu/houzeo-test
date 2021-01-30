<?php

namespace App\Listeners;

use Mail;
use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
		$user = $event->user;
		
        $data = array('name'=>$user['name'], 'email'=>$user['email']);
        Mail::send('emails.newUser', $data, function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('New User Registered');
        });
    }
}
