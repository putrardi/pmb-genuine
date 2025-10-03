<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use Illuminate\Http\Request;

class AdminPendaftarController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $gActive = Gelombang::where('aktif', true)
            ->where('mulai','<=',$today)
            ->where('selesai','>=',$today)
            ->orderBy('mulai','desc')->first();

        $q = trim((string)$request->get('q',''));
        $status = $request->get('status',''); // draft/submitted/verified/rejected

        $items = Pendaftaran::with(['user','gelombang','prodi'])
            ->when($gActive, fn($qb)=>$qb->where('gelombang_id',$gActive->id))
            ->when($q !== '', function($qb) use ($q){
                $qb->where(function($w) use ($q){
                    $w->where('no_reg','like',"%$q%")
                      ->orWhereHas('user', fn($u)=>$u->where('name','like',"%$q%")->orWhere('email','like',"%$q%"));
                });
            })
            ->when($status !== '', fn($qb)=>$qb->where('status',$status))
            ->orderByDesc('submitted_at')
            ->paginate(20)->withQueryString();

        return view('admin.pendaftar.index', compact('items','gActive','q','status'));
    }
}
