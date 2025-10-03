@component('mail::message')
# Pendaftaran Dikirim

Halo {{ $p->user?->name ?? 'Calon Mahasiswa' }},

Pendaftaran Anda dengan nomor **{{ $p->no_reg }}** telah berhasil dikirim (status: **SUBMITTED**).

@if($p->gelombang)
**Gelombang:** {{ $p->gelombang->nama }} ({{ $p->gelombang->mulai->format('d M Y') }} – {{ $p->gelombang->selesai->format('d M Y') }})
@endif

@component('mail::button', ['url' => $urlRingkasan])
Lihat Ringkasan
@endcomponent

Terima kasih,  
**Panitia PMB Genuine**
@endcomponent
