<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $peserta;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $peserta, $password)
    {
        $this->user = $user;
        $this->peserta = $peserta;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Akun anda sudah berhasil dibuat:D')
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,
                        'peserta' => $this->peserta,
                        'password' => $this->password,
                    ]);
    }
}
