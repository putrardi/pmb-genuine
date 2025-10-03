@component('mail::message')
# Status Pendaftaran: {{ strtoupper($p->status) }}

Halo {{ $p->user?->name ?? 'Calon Mahasiswa' }},

Status pendaftaran **{{ $p->no_reg }}** kini: **{{ strtoupper($p->status) }}**.

@if(!empty($p->verification_note))
**Catatan:**  
{{ $p->verification_note }}
@endif

@component('mail::button', ['url' => $urlRingkasan])
Lihat Ringkasan
@endcomponent

Terima kasih,  
**Panitia PMB Genuine**
@endcomponent
