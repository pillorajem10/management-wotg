<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DgroupMemberApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $dgroupLeader;
    public $memberEmail;
    public $approvalToken;
    public $dgroupLeaderId;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $dgroupLeader
     * @param string $memberEmail
     * @param string $approvalToken
     */
    public function __construct($dgroupLeader, $memberEmail, $approvalToken, $dgroupLeaderId)
    {
        $this->dgroupLeader = $dgroupLeader;
        $this->memberEmail = $memberEmail;
        $this->approvalToken = $approvalToken;
        $this->dgroupLeaderId = $dgroupLeaderId;  // Store the D-Group leader's ID
    }
    

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('D-Group member approval request')
                    ->view('emails.dgroupMemberApprovalRequest')
                    ->with([
                        'dgroupLeader' => $this->dgroupLeader,
                        'memberEmail' => $this->memberEmail,
                        'approvalToken' => $this->approvalToken,
                        'dgroupLeaderId' => $this->dgroupLeaderId,
                    ]);
    }    
}



