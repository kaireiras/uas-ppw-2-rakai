<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PekerjaanController extends Controller
{
    public function index(Request $request) 
    {
        $keyword = $request->keyword;
        $showDeleted = $request->boolean('deleted');

        $query = Pekerjaan::withCount(['pegawai'=>function($q){
            $q->whereNull('deleted_at');
        }]);

        if ($showDeleted) {
            $query->onlyTrashed();
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $data = $query->paginate(7)->withQueryString();

        return view('pekerjaan.index', compact('data'));
    }


    public function add() {
        return view('pekerjaan.add');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) return redirect()->back()->with($validator->errors()->all());

        $data = new Pekerjaan();
        $data->nama = $request->nama;
        $data->deskripsi = $request->deskripsi;

        if ($data->save()) {
            return redirect()->route('pekerjaan.index')->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->route('pekerjaan.index')->with('success', 'Data tidak tersimpan');
        }
    }

    public function edit(Request $request) {
        $data = Pekerjaan::findOrFail($request->id);
        return view('pekerjaan.edit', compact('data'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) return redirect()->back()->with($validator->errors()->all());

        $data = Pekerjaan::findOrFail($request->id);

        $data->nama = $request->nama;
        $data->deskripsi = $request->deskripsi;

        if ($data->save()) {
            return redirect()->route('pekerjaan.index')->with('success', 'Data sudah teredit');
        } else {
            return redirect()->route('pekerjaan.index')->with('success', 'Data tidak tersimpan');
        }
    }

    public function restore($id){
        Pekerjaan::withTrashed()->findOrFail($id)->restore();
        return redirect()->back()->with('success', 'Data berhasil dipulihkan');
    }

    public function destroy(Request $request) {
        Pekerjaan::findOrFail($request->id)->delete();
        return redirect()->route('pekerjaan.index')->with('success', 'Data terhapus');
    }
}
