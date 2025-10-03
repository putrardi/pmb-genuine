<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Master\Models\ProgramStudi;
use App\Http\Requests\Admin\ProgramStudiRequest;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q',''));
        $items = ProgramStudi::query()
            ->when($q !== '', function($qb) use ($q) {
                $qb->where('kode','like',"%$q%")
                   ->orWhere('nama','like',"%$q%")
                   ->orWhere('jenjang','like',"%$q%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.prodi.index', compact('items','q'));
    }

    public function create()
    {
        return view('admin.prodi.form', ['item' => new ProgramStudi()]);
    }

    public function store(ProgramStudiRequest $request)
    {
        ProgramStudi::create($request->validated());
        return redirect()->route('admin.prodi.index')->with('success','Program Studi ditambahkan.');
    }

    public function edit(ProgramStudi $program_studi)
    {
        return view('admin.prodi.form', ['item' => $program_studi]);
    }

    public function update(ProgramStudiRequest $request, ProgramStudi $program_studi)
    {
        $program_studi->update($request->validated());
        return redirect()->route('admin.prodi.index')->with('success','Program Studi diperbarui.');
    }

    public function destroy(ProgramStudi $program_studi)
    {
        $program_studi->delete();
        return redirect()->route('admin.prodi.index')->with('success','Program Studi dihapus.');
    }
}
