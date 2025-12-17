<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pegawai extends Model
{
    use SoftDeletes;
    protected $table = 'pegawai';

    protected $fillable = [
        'pekerjaan_id',
        'nama',
        'email',
        'gender',
        'is_active',
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}
