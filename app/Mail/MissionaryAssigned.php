<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissionaryAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $seekerName;
    public $missionaryFirstName;  // Add this property

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($seekerName, $missionaryFirstName)
    {
        $this->seekerName = $seekerName;
        $this->missionaryFirstName = $missionaryFirstName;  // Assign the missionary's first name
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
                        'missionaryFirstName' => $this->missionaryFirstName,  // Pass to the view
                    ]);
    }
}
