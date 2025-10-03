<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Master\Models\Gelombang;
use App\Http\Requests\Admin\GelombangRequest;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    // Middleware auth+role:admin ditangani via routes (Laravel 11/12 style)

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q',''));
        $items = Gelombang::query()
            ->when($q !== '', function($qb) use ($q) {
                $qb->where('nama','like',"%$q%");
            })
            ->orderByDesc('mulai')
            ->paginate(10)->withQueryString();

        return view('admin.gelombang.index', compact('items','q'));
    }

    public function create()
    {
        return view('admin.gelombang.form', ['item' => new Gelombang()]);
    }

    public function store(GelombangRequest $request)
    {
        Gelombang::create($request->validated());
        return redirect()->route('admin.gelombang.index')->with('success','Gelombang ditambahkan.');
    }

    public function edit(Gelombang $gelombang)
    {
        return view('admin.gelombang.form', ['item' => $gelombang]);
    }

    public function update(GelombangRequest $request, Gelombang $gelombang)
    {
        $gelombang->update($request->validated());
        return redirect()->route('admin.gelombang.index')->with('success','Gelombang diperbarui.');
    }

    public function destroy(Gelombang $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('admin.gelombang.index')->with('success','Gelombang dihapus.');
    }
}
