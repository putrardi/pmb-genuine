<?php

namespace App\Domain\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Pendaftaran\Models\Pendaftaran;

class ProgramStudi extends Model
{
    // Karena nama tabel non-standar
    protected $table = 'program_studi';

    protected $fillable = ['kode','nama','jenjang','kuota','aktif'];

    protected $casts = [
        'aktif' => 'boolean',
        'kuota' => 'integer',
    ];

    /* ===========================
     |  Scopes
     |===========================*/
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /* ===========================
     |  Kuota helpers
     |===========================*/

    /**
     * Jumlah pendaftar yang sudah VERIFIED pada prodi ini.
     */
    public function verifiedFilled(): int
    {
        return Pendaftaran::where('prodi_id', $this->id)
            ->where('status', 'verified')
            ->count();
    }

    /**
     * Apakah masih ada kursi tersisa (verified < kuota).
     */
    public function hasAvailableSeat(): bool
    {
        return $this->verifiedFilled() < (int) $this->kuota;
    }

    /**
     * Sisa kursi (tidak akan negatif).
     */
    public function seatsLeft(): int
    {
        return max(((int) $this->kuota) - $this->verifiedFilled(), 0);
    }
}
