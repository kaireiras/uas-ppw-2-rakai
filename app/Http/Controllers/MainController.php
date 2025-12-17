<?php

namespace App\Http\Controllers;
use App\Models\Pegawai;
use App\Models\Pekerjaan;

class MainController extends Controller
{
    public function index() {
        $genderLabels = ['male', 'female'];
        $genderData = [];
        foreach ($genderLabels as $gender){
            $genderData[] = Pegawai::where('gender', $gender)->count();
        }

        $pekerjaan = Pekerjaan::all();
        $pekerjaanLabels = [];
        $pekerjaanData = [];
        foreach($pekerjaan as $p){
            $pekerjaanLabels[] = $p->nama;
            $pekerjaanData[] = Pegawai::where('pekerjaan_id', $p->id)->count();
        }

        return view('index', compact(
            'genderLabels', 'genderData', 'pekerjaanLabels', 'pekerjaanData',
        ));
    }
}
