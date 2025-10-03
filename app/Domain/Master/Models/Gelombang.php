<?php

namespace App\Domain\Master\Models;

use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    protected $table = 'gelombang_pendaftaran';

    protected $fillable = ['nama','mulai','selesai','biaya','aktif'];

    protected $casts = [
        'mulai'  => 'date',
        'selesai'=> 'date',
        'aktif'  => 'boolean',
        'biaya'  => 'integer',
    ];

    /** Cek apakah ada gelombang aktif lain yang overlap dengan periode ini */
    public static function existsActiveOverlap(?int $ignoreId, string $mulai, string $selesai): bool
    {
        return static::query()
            ->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
            ->where('aktif', true)
            ->where(function ($q) use ($mulai, $selesai) {
                // overlap: A.mulai <= B.selesai && A.selesai >= B.mulai
                $q->where('mulai','<=',$selesai)->where('selesai','>=',$mulai);
            })
            ->exists();
    }

    /** Apakah sudah ada gelombang aktif lain (kapan pun) */
    public static function existsAnotherActive(?int $ignoreId): bool
    {
        return static::query()
            ->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
            ->where('aktif', true)
            ->exists();
    }
}
