<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use App\Domain\Master\Models\ProgramStudi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Gelombang aktif hari ini
        $today = now()->toDateString();
        $gActive = Gelombang::where('aktif', true)
            ->where('mulai','<=',$today)
            ->where('selesai','>=',$today)
            ->orderBy('mulai','desc')->first();

        // KPI ringkas
        $base = Pendaftaran::query();
        if ($gActive) $base->where('gelombang_id', $gActive->id);
        $total     = (clone $base)->count();
        $submitted = (clone $base)->where('status','submitted')->count();
        $verified  = (clone $base)->where('status','verified')->count();
        $rejected  = (clone $base)->where('status','rejected')->count();

        // By gender (ambil dari biodata JSON: jenis_kelamin)
        $byGender = (clone $base)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(biodata, '$.jenis_kelamin')) as jk, COUNT(*) as c")
            ->groupBy('jk')->pluck('c','jk');
        $genderLabels = ['L','P'];
        $genderData   = [ (int)($byGender['L'] ?? 0), (int)($byGender['P'] ?? 0) ];

        // By prodi (relasi prodi_id)
        $byProdi = (clone $base)
            ->select('prodi_id', DB::raw('COUNT(*) as c'))
            ->groupBy('prodi_id')->pluck('c','prodi_id')->all();
        $prodis   = ProgramStudi::whereIn('id', array_keys($byProdi ?: []))->get()->keyBy('id');
        $prodiLabels = [];
        $prodiData   = [];
        foreach ($byProdi as $pid => $cnt) {
            $prodiLabels[] = $prodis[$pid]->nama ?? '—';
            $prodiData[]   = (int)$cnt;
        }

        // Tren registrasi / submit 14 hari terakhir
        $dateLabels = [];
        $countSubmit = [];
        for ($i=13; $i>=0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dateLabels[] = $d->format('d M');
            $q = (clone $base)->whereDate('submitted_at', $d->toDateString())->count();
            $countSubmit[] = $q;
        }

        return view('admin.dashboard', compact(
            'gActive','total','submitted','verified','rejected',
            'genderLabels','genderData','prodiLabels','prodiData',
            'dateLabels','countSubmit'
        ));
    }
}
