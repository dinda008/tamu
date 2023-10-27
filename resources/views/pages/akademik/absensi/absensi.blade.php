@extends('components.main')
@section('breadcrumbs')
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="/dashboard">Dashboard</a>
        </li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"></li>
    </ol>
    <h6 class="font-weight-bolder mb-0">Data Siswa </h6>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Absensi</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive pb-2 px-3">

                        <table id="example" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Tahun Akademik</th>
                                    <th
                                        class="
                                            text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($akademiks as $akademik)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $akademik->tahun_ajaran }}
                                        </td>

                                        <td class="text-center" style="width: 20%;">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success dropdown-toggle text-sm"
                                                    data-bs-toggle="dropdown" aria-expanded="true" data-boundary="viewport">
                                                    <strong>Lihat Absen</strong>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item"
                                                            href="/akademik/absensi/{{ str_replace('/', '-', $akademik->tahun_ajaran) }}/x">Kelas
                                                            X</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="/akademik/absensi/{{ str_replace('/', '-', $akademik->tahun_ajaran) }}/xi">Kelas
                                                            XI</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="/akademik/absensi/{{ str_replace('/', '-', $akademik->tahun_ajaran) }}/xii">Kelas
                                                            XII</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- footer --}}
