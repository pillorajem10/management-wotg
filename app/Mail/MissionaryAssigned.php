<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissionaryAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $seekerName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($seekerName)
    {
        $this->seekerName = $seekerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.missionary_assigned')
                    ->subject('New Seeker Assigned to You')
                    ->with([
                        'seekerName' => $this->seekerName,
                    ]);
    }
}
