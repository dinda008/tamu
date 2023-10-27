<?php

namespace App\Http\Controllers;

use App\Models\tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
   public function index() {
      return view('pages.humas.tamu', [
         'tamu' => tamu::orderBy('created_at')->get(),
     ])->with('title', 'Tamu'); 
   }

   public function edit(tamu $tamu)
   {
      return view('pages.humas.edit', [
         'id',
         'name',
         'asal',
         'tujuan' => ['kepala sekolah','waka'],
         'keterangan',
         'tamu' => $tamu
      ])->with('title', 'Update Data Tamu');
   }

   public function delete(tamu $tamu)
   {
      $tamu->update(['deleted => 1']);
      tamu::find($tamu->user->id)->delete();

      return redirect('/humas/tamu')->with('toast_success', 'Data Tamu Berhasil Dihapus');
   }
}
