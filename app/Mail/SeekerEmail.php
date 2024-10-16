<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SeekerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $seeker;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     *
     * @param $seeker
     * @param $subject
     * @param $body
     */
    public function __construct($seeker, $subject, $body)
    {
        $this->seeker = $seeker;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@wotgonline.com', 'WOTG')
                    ->subject($this->subject) // Use the custom subject
                    ->view('emails.seekerEmail') // Ensure the view is correct
                    ->with(['body' => $this->body]); // Pass the body to the view
    }
}
