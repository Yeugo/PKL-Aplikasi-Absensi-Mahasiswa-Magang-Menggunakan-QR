<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $peserta;
    public $password;
    public $pendaftaran;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $peserta, $password, $pendaftaran)
    {
        $this->user = $user;
        $this->peserta = $peserta;
        $this->password = $password;
        $this->pendaftaran = $pendaftaran;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $qrData = "Hj. Ruziah, SE, M.AP\nSekretaris DKP3\nNIP. 19680714 199503 2 004";
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        // Generate PDF dari view, misal: 'pdf.surat_balasan'
        $pdf = Pdf::loadView('pdf.surat_balasan', [
            'user' => $this->user,
            'peserta' => $this->peserta,
            'qrCode' => $qrCode,
            'pendaftaran' => $this->pendaftaran,
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
