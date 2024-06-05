@extends('layouts.admin.index')
@section('title','Data Absensi Karyawan')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Izin / Sakit
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nik</th>
                                            <th>Nama</th>
                                            <th>Tanggal Izin</th>
                                            <th>Alasan</th>
                                            <th>Keterangan</th>
                                            <th>Status Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izinsakit as $is )
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $is->nik }}</td>
                                                <td>{{ $is->name }}</td>
                                                <td>{{ date('d-m-Y',strtotime($is->date_izin)) }}</td>
                                                <td>{{ $is->status == "1" ? "Izin" : "Sakit" }}</td>
                                                <td>{{ $is->keterangan }}</td>
                                                <td>
                                                    @if ($is->status_approved == 1)
                                                        <span class="badge text-bg-success">Distujui</span>
                                                    @elseif ($is->status_approved == 2)
                                                        <span class="badge text-bg-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge text-bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($is->status_approved == 0)
                                                        <a href="#" class="btn btn-sm btn-primary" id="approve" id_izinsakit="{{ $is->id }}">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-external-link"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>
                                                        </a>
                                                    @else
                                                        <a href="/presensi/{{ $is->id }}/batalizinsakit" class="btn btn-sm btn-danger">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-square-rounded-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10l4 4m0 -4l-4 4" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                                                            Batalkan
                                                        </a>
                                                    @endif
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
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-izinsakit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Izin / Sakit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/presensi/approveizinsakit" method="POST">
                @csrf
                <input type="hidden" name="id_izinsakit_form" id="id_izinsakit_form">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="status_approved" id="status_approved" class="form-select">
                                <option value="1">Disetujui</option>
                                <option value="2">Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100 mt-3" type="submit">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection

@push('myscript')
    <script>
        $(function(){
            $("#approve").click(function(e){
                e.preventDefault();
                var id_izinsakit = $(this).attr("id_izinsakit");
                $("#id_izinsakit_form").val(id_izinsakit);
                $("#modal-izinsakit").modal("show");
            });
        });
    </script>
@endpush
    