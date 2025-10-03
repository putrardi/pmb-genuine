<?php

namespace App\Domain\Master\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi'; // karena kita pakai nama tabel non-standar
    protected $fillable = ['kode','nama','jenjang','kuota','aktif'];
    protected $casts = ['aktif'=>'boolean','kuota'=>'integer'];
}
