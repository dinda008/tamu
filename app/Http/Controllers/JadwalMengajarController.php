<?php

namespace App\Http\Controllers;

use App\Models\Detail_jadwal;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        $guru = Guru::All();
        return view('pages.datajadwalmengajar.jadwal', [
            'guru'      => $guru,
        ]);
    }
    public function atur($id)
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $jadwal =  Jadwal::where("guru_id", $id)->orderBy("hari", 'asc')->get();
        $guru  =   Guru::find($id);
        $mapel  =  Mapel::select('id', 'namamapel')->get();
        $kelas   =  Kelas::select('id', 'namakelas')->get();
        return view('pages.datajadwalmengajar.atur', [
            'guru'      => $guru,
            // 'mapel'     => $mapel,
            'jadwal'    => $jadwal,
            'kelas'     => $kelas,
            'hari'      => $hari

        ]);
    }
    public function store(Request $request)
    {
        $jadwal = $request->all();
        Jadwal::create($jadwal);
        return Redirect::back()->with('toast_success', 'Data berhasil ditambahkan !');
    }
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);
        $jadwaldata = $request->all();
        $jadwal->update($jadwaldata);
        return Redirect::back()->with('toast_success', 'Data berhasil diubah !');
    }
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        $jadwal->delete();
        return Redirect::back()->with('toast_success', 'Data berhasil dihapus !');
    }

    public function cetak($id)
    {
        $jadwal =  Jadwal::select('hari', 'guru_id')->where("guru_id", $id)->groupBy('hari', 'guru_id')->orderBy("hari", 'asc')->get();
        $data = [];
        $no = 0;
        foreach ($jadwal as $item) {
            $data[$no] = $item;
            $data[$no]->hari = $item->hari;
            $data[$no]->detail = Jadwal::where(['guru_id' => $item->guru_id, 'hari' => $item->hari])->get();
            $no++;
        }
        // echo json_encode($data);
        // die;
        $guru  =  Guru::find($id);

        $kelas =  Kelas::select('id', 'namakelas')->get();

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pages.datajadwalmengajar.cetak_pdf', [
            'guru'      => $guru,
            'jadwal'    => $data,
            'kelas'     => $kelas,
            'hari'      => $hari
        ]);
        return $pdf->stream('laporan-jadwal-mengajar-pdf');
    }
    public function jadwalguru()
    {
        $guruId = auth()->user()->guru->id;
        $detail_jadwal = Detail_jadwal::todaySchedule($guruId);

        $hari_list = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );
        $hari_ini = strtolower($hari_list[Carbon::now()->dayOfWeek]);

        $all_jadwal = Detail_jadwal::whereHas('jadwal', function ($query) use ($hari_ini) {
            $query->whereHas('akademik', function ($query) {
                $query->where('selected', 1);
            });
            $query->where('hari', $hari_ini);
        })->orderBy('jam_mulai', 'asc')->get();


        return view('pages.akademik.data-jadwal-guru.jadwalguru', [
            'jadwals' => $detail_jadwal,
            'all_jadwal' => $all_jadwal,
        ])->with('title', 'Jadwal Mengajar');
    }
    public function cetakjadwalguru($id)
    {
        $jadwal =  Jadwal::select('hari', 'guru_id')->where("guru_id", $id)->groupBy('hari', 'guru_id')->orderBy("hari", 'asc')->get();
        $data = [];
        $no = 0;
        foreach ($jadwal as $item) {
            $data[$no] = $item;
            $data[$no]->hari = $item->hari;
            $data[$no]->detail = Jadwal::where(['guru_id' => $item->guru_id, 'hari' => $item->hari])->get();
            $no++;
        }
        // echo json_encode($data);
        // die;
        $guru  =  Guru::find($id);

        $kelas =  Kelas::select('id', 'namakelas')->get();

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pages.datajadwalmengajar.cetak', [
            'guru'      => $guru,
            'jadwal'    => $data,
            'kelas'     => $kelas,
            'hari'      => $hari
        ]);
        return $pdf->stream('pages.laporan-jadwal-mengajar-pdf');
    }

    public function lihat()
    {
        $guru = Guru::All();
        return view('pages.datajadwalmengajar.lihat', [
            'guru'      => $guru,
        ]);
    }

    public function cekjadwal($id)
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $jadwal =  Jadwal::where("guru_id", $id)->orderBy("hari", 'asc')->get();
        $guru  =   Guru::find($id);
        $mapel  =  Mapel::select('id', 'namamapel')->get();
        $kelas   =  Kelas::select('id', 'namakelas')->get();
        return view('pages.datajadwalmengajar.cekjadwal', [
            'guru'      => $guru,
            // 'mapel'     => $mapel,
            'jadwal'    => $jadwal,
            'kelas'     => $kelas,
            'hari'      => $hari

        ]);
    }

    public function cek($id)
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $jadwal =  Jadwal::where("guru_id", $id)->orderBy("hari", 'asc')->get();
        $guru  =   Guru::find($id);
        $mapel  =  Mapel::select('id', 'namamapel')->get();
        $kelas   =  Kelas::select('id', 'namakelas')->get();
        return view('pages.datajadwalmengajar.cek', [
            'guru'      => $guru,
            // 'mapel'     => $mapel,
            'jadwal'    => $jadwal,
            'kelas'     => $kelas,
            'hari'      => $hari

        ]);
    }
}
