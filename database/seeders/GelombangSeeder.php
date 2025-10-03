<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Master\Models\Gelombang;

class GelombangSeeder extends Seeder
{
    public function run(): void
    {
        Gelombang::updateOrCreate(
            ['nama' => 'Gelombang 1'],
            ['mulai' => now()->startOfMonth()->toDateString(),
             'selesai' => now()->endOfMonth()->toDateString(),
             'biaya' => 200000,
             'aktif' => true]
        );
    }
}
