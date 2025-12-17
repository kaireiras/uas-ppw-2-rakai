<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mews\Captcha\Facades\Captcha;

class PegawaiController extends Controller
{
    public function index(Request $request){
        $keyword = $request->keyword;
        $showDeleted = $request->boolean('deleted');

        $query = Pegawai::with('pekerjaan');

        if ($showDeleted) {
            $query->onlyTrashed();
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $data = $query->paginate(7)->withQueryString();

        return view('pegawai.index', compact('data'));
    }

    public function add(){
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.add', compact('pekerjaan'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email',
            'gender' => 'required|in:male,female',
            'pekerjaan_id'=> 'required|exists:pekerjaan,id',
            'is_active' => 'required|boolean',
            'captcha'=>'required|captcha',
        ]);

        if($validator->fails()) 
            return redirect()->back()->withErrors($validator)->withInput();

        $data = new Pegawai();
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->is_active = $request->is_active;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan');
        } else {
            return redirect()->route('pegawai.index')->with('success', 'Pegawai tidak tersimpan');
        }
    }

    public function edit(Request $request){
        $data = Pegawai::findOrFail($request->id);
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.edit', compact('data', 'pekerjaan'));
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('pegawai', 'email')->ignore($request->id),
            ],
            'gender' => 'required|in:male,female',
            'pekerjaan_id'=> 'required|exists:pekerjaan,id',
            'is_active' => 'required|boolean'
        ]);

        if($validator->fails()) 
            return redirect()->back()->withErrors($validator)->withInput();

        $data = Pegawai::findOrFail($request->id);
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->is_active = $request->is_active;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Data pegawai telah teredit');
        } else {
            return redirect()->route('pegawai.index')->with('success', 'Data pegawai tidak tersimpan');
        }
    }

    public function destroy(Request $request){
        Pegawai::findOrFail($request->id)->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai terhapus');
    }

    public function restore($id){
        Pegawai::withTrashed()->findOrFail($id)->restore();
        return redirect()->back()->with('success', 'Data pegawai berhasil dipulihkan');
    }
}
