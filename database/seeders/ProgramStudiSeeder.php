<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Master\Models\ProgramStudi;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['kode'=>'TI-S1','nama'=>'Teknik Informatika','jenjang'=>'S1','kuota'=>120,'aktif'=>true],
            ['kode'=>'SI-S1','nama'=>'Sistem Informasi','jenjang'=>'S1','kuota'=>100,'aktif'=>true],
            ['kode'=>'KA-D3','nama'=>'Komputerisasi Akuntansi','jenjang'=>'D3','kuota'=>60,'aktif'=>false],
        ];
        foreach ($rows as $r) { ProgramStudi::updateOrCreate(['kode'=>$r['kode']], $r); }
    }
}
