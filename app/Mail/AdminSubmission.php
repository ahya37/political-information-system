<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminSubmission extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user_id;

    public function __construct(User $user_id)
    {
         $this->user_id = $user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user_id;

        return $this->from($user->email)
                    ->subject('Pengajuan admin baru disistem AAW')
                    ->view('emails.adminsubmission')
                    ->with([
                        'user' => $this->user_id
                    ]);
    }
}
