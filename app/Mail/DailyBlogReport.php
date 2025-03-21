<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyBlogReport extends Mailable
{
    use Queueable, SerializesModels;

    public $blog; // Blog instance
    public $firstName; // First name of the recipient
    public $blogIntro; // Modified blog intro

    /**
     * Create a new message instance.
     *
     * @param  mixed  $blog
     * @param  string  $firstName
     * @param  string  $blogIntro
     * @return void
     */
    public function __construct($blog, $firstName, $blogIntro)
    {
        $this->blog = $blog; // Assign the blog to the property
        $this->firstName = $firstName; // Assign the first name to the property
        $this->blogIntro = $blogIntro; // Assign the modified intro to the property
    }

    public function build()
    {
        return $this->subject($this->blog->blog_title) // Access the blog title
                    ->view('emails.dailyBlogReport') // The view for the email
                    ->with([
                        'blog' => $this->blog,
                        'firstName' => $this->firstName, // Pass the first name to the view
                        'blogIntro' => $this->blogIntro, // Pass the modified blog intro to the view
                    ]);
    }
}
