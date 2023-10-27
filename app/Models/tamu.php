<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tamu extends Model
{
    use HasFactory;

    protected $table = 'tamus';
    protected $fillable = [
        'id',
        'name',
        'asal',
        'tujuan',
        'keterangan',
        'created_at',
        'updated_at'
    ];
}
