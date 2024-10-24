<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyBlogReport extends Mailable
{
    use Queueable, SerializesModels;

    public $blog; // Blog instance

    /**
     * Create a new message instance.
     *
     * @param  mixed  $blog
     * @return void
     */
    public function __construct($blog)
    {
        $this->blog = $blog; // Assign the blog to the property
    }

    public function build()
    {
        // You could also include a custom view or use a layout
        return $this->subject($this->blog->blog_title) // Access the blog title
                    ->view('emails.dailyBlogReport') // The view for the email
                    ->with(['blog' => $this->blog]);
    }
}
