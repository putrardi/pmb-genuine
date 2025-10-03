<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domain\Pendaftaran\Models\Pendaftaran;

class PendaftaranStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Pendaftaran $pendaftaran) {}

    public function build()
    {
        $upper = strtoupper($this->pendaftaran->status);
        return $this->subject("Status Pendaftaran {$upper} • ".$this->pendaftaran->no_reg)
            ->markdown('emails.pendaftaran.status', [
                'p' => $this->pendaftaran,
                'urlRingkasan' => route('calon.ringkasan'),
            ]);
    }
}
