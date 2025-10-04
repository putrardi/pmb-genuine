<?php

namespace App\Domain\Pendaftaran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Domain\Master\Models\Gelombang;
use App\Domain\Master\Models\ProgramStudi;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id','no_reg','status','biodata','submitted_at','gelombang_id','dokumen','prodi_id',
    ];

    protected $casts = [
    'biodata'      => 'array',
    'dokumen'      => 'array',
    'submitted_at' => 'datetime',
    'verified_at'  => 'datetime',
    ];


    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function gelombang(): BelongsTo { return $this->belongsTo(Gelombang::class); }

    public function isDraft(): bool     { return $this->status === 'draft'; }
    public function isSubmitted(): bool { return $this->status === 'submitted'; }

    public function hasBiodata(): bool
    {
    $b = $this->biodata ?? [];
    return !empty($b['nik'] ?? null) || !empty($b['nama_lengkap'] ?? null);
    }

    public function hasAllDocs(): bool
    {
        $d = $this->dokumen ?? [];
        return !empty($d['ktp']['path'] ?? null)
            && !empty($d['ijazah']['path'] ?? null)
            && !empty($d['pas_foto']['path'] ?? null);
    }

        /**
     * SUBMITTED/VERIFIED => 100%. REJECTED => pakai perhitungan langkah (maks 67%).
     */
    public function progressPercent(): int
    {
    if (in_array($this->status, ['submitted','verified'], true)) {
        return 100;
    }
    $stepsDone = 0;
    if ($this->hasBiodata()) $stepsDone++;
    if ($this->hasAllDocs()) $stepsDone++;
    if ($this->hasChosenGelombangProdi()) $stepsDone++;
    return (int) round(($stepsDone / 3) * 100);
    }

    /** REJECTED boleh submit ulang */
    public function canResubmit(): bool
    {
    return $this->status === 'rejected';
    }

    public function hasChosenGelombangProdi(): bool
    {
    return !empty($this->gelombang_id) && !empty($this->prodi_id);
    }

    public function hasCompleteBiodata(): bool
    {
        $b = $this->biodata ?? [];
        foreach (['nik','nama_lengkap','jenis_kelamin','tanggal_lahir','no_hp','alamat','kabupaten','provinsi','sekolah_asal','tahun_lulus'] as $k) {
            if (empty($b[$k])) return false;
        }
        return true;
    }

    public function prodi() { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    // helper:
    public function hasChosenProdi(): bool {return !empty($this->prodi_id);}
    public function isVerified(): bool { return $this->status === 'verified'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function isLockedForEdits(): bool
    {
    return in_array($this->status, ['submitted', 'verified'], true);
    // Jika maunya hanya saat VERIFIED:
    // return $this->status === 'verified';
    }
    
    public function verifiedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(\App\Models\User::class,'verified_by');
}

}
