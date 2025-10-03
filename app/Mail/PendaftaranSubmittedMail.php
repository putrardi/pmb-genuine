<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domain\Pendaftaran\Models\Pendaftaran;

class PendaftaranSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Pendaftaran $pendaftaran) {}

    public function build()
    {
        return $this->subject('Pendaftaran Dikirim • '.$this->pendaftaran->no_reg)
            ->markdown('emails.pendaftaran.submitted', [
                'p' => $this->pendaftaran,
                'urlRingkasan' => route('calon.ringkasan'),
            ]);
    }
}
