<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        // Generate PDF dari view, misal: 'pdf.surat_balasan'
        $pdf = Pdf::loadView('pdf.surat_balasan', [
            'user' => $this->user,
            'peserta' => $this->peserta,
        ])->output();

        return $this->subject('Akun anda sudah berhasil dibuat:D')
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,
                        'peserta' => $this->peserta,
                        'password' => $this->password,
                    ])
                    ->attachData($pdf, 'Surat Balasan - ' . ($this->peserta->name ?? 'peserta') . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
