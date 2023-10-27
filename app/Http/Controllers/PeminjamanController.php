<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peminjaman = Peminjaman::whereDate('tanggal_pengembalian', '>=', now())
            ->orderBy('tanggal_peminjaman', 'asc')
            ->get();

        $hariini = Peminjaman::whereDate('tanggal_peminjaman', '<=', now())
            ->whereDate('tanggal_pengembalian', '>=', now())
            ->orderBy('tanggal_peminjaman', 'asc')
            ->get();

        $ruang = Ruang::all();

        return view('pages.humas.peminjaman-ruang.peminjaman', [
            'hariini'      => $hariini,
            'peminjaman'   => $peminjaman,
            'ruang'        => $ruang
        ])->with('title', 'Data Peminjaman');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     Peminjaman::create([
    //         'ruang_id'              => $request->ruang,
    //         'nama_peminjam'         => $request->nama_peminjam,
    //         'tanggal_peminjaman'    => $request->tgl_peminjaman,
    //         'tanggal_pengembalian'  => $request->tgl_pengembalian,
    //     ]);
    //     // return request('lokasi');
    //     return redirect('/data-peminjaman')->with('toast_success', 'Data Ruang Berhasil di Tambahkan');
    // }

    public function store(Request $request)
    {
        // Validasi apakah ruang sudah terpinjam pada rentang waktu yang sama
        $validator = Validator::make($request->all(), [
            'ruang' => 'required',
            'nama_peminjam' => 'required',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'required|date|after_or_equal:tgl_peminjaman',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi ketersediaan ruangan
        $existingPeminjaman = Peminjaman::where('ruang_id', $request->ruang)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('tanggal_peminjaman', '>=', $request->tgl_peminjaman)
                        ->where('tanggal_peminjaman', '<=', $request->tgl_pengembalian);
                })
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_pengembalian', '>=', $request->tgl_peminjaman)
                            ->where('tanggal_pengembalian', '<=', $request->tgl_pengembalian);
                    });
            })
            ->first();

        if ($existingPeminjaman) {
            return redirect()->back()->with('toast_error', 'Ruangan sudah terpinjam pada rentang waktu yang sama');
        }

        Peminjaman::create([
            'ruang_id' => $request->ruang,
            'nama_peminjam' => $request->nama_peminjam,
            'tanggal_peminjaman' => $request->tgl_peminjaman,
            'tanggal_pengembalian' => $request->tgl_pengembalian,
        ]);

        return redirect('/data-peminjaman')->with('toast_success', 'Data Ruang Berhasil di Tambahkan');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Peminjaman $peminjaman)
    // {
    //     $peminjaman = Peminjaman::find($request->id_peminjaman);
    //     $data = [
    //         'ruang_id' => $request->ruang,
    //         'nama_peminjam' => $request->nama_peminjam,
    //         'tanggal_peminjaman' => $request->tgl_peminjaman,
    //         'tanggal_pengembalian' => $request->tgl_pengembalian,
    //     ];

    //     $peminjaman->update($data);
    //     return redirect('/data-peminjaman')->with('toast_success', 'Data Ruang Berhasil di Ubah');
    // }
    public function update(Request $request, Peminjaman $peminjaman)
    {
        // Validasi apakah ruang sudah terpinjam pada rentang waktu yang sama
        $validator = Validator::make($request->all(), [
            'ruang' => 'required',
            'nama_peminjam' => 'required',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'required|date|after_or_equal:tgl_peminjaman',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi ketersediaan ruangan
        $existingPeminjaman = Peminjaman::where('ruang_id', $request->ruang)
            ->where(function ($query) use ($request, $peminjaman) {
                $query->where(function ($q) use ($request) {
                    $q->where('tanggal_peminjaman', '>=', $request->tgl_peminjaman)
                        ->where('tanggal_peminjaman', '<=', $request->tgl_pengembalian);
                })
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_pengembalian', '>=', $request->tgl_peminjaman)
                            ->where('tanggal_pengembalian', '<=', $request->tgl_pengembalian);
                    });
            })
            ->where('id', '!=', $peminjaman->id)
            ->first();

        if ($existingPeminjaman) {
            return redirect()->back()->with('toast_error', 'Ruangan sudah terpinjam pada rentang waktu yang sama');
        }

        $data = [
            'ruang_id' => $request->ruang,
            'nama_peminjam' => $request->nama_peminjam,
            'tanggal_peminjaman' => $request->tgl_peminjaman,
            'tanggal_pengembalian' => $request->tgl_pengembalian,
        ];

        $peminjaman->update($data);
        return redirect('/data-peminjaman')->with('toast_success', 'Data Ruang Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        //delete data
        $peminjaman->delete();

        return redirect('/data-peminjaman')->with('toast_success', 'Data Pinjam Berhasil di Hapus');
    }

    public function history()
    {
        $peminjaman = Peminjaman::whereDate('tanggal_pengembalian', '<', now())
            ->get();
        return view('pages.humas.peminjaman-ruang.history', [
            'peminjaman'   => $peminjaman
        ])->with('title', 'Data Peminjaman');
    }
}
