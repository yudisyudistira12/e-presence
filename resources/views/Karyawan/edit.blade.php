@extends('layouts.admin.index')
@section('title','Edit Data Karyawan')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Edit Data Karyawan
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
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                                                </span>
                                                <input type="text" id="nik" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan (NIK)" value="{{ $karyawan->nik }}">
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                                                </span>
                                                <input type="text" id="name" class="form-control" name="name" placeholder="Nama Lengkap Karyawan" value="{{ $karyawan->name }}">
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                                </span>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $karyawan->email }}">
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                </span>
                                                <input type="tel" name="no_hp" id="no_hp" class="form-control" placeholder="No Handphone Aktif Karyawan" value="{{ $karyawan->no_hp }}">
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                                        <div class="col-12">
                                            <select name="role" id="role" class="form-select">
                                                <option @if($karyawan->role == 'Karyawan') selected @endif value="Karyawan">Karyawan</option>
                                                <option @if($karyawan->role == 'Admin') selected @endif value="Admin">Admin</option>
                                            </select>
                                        </div>
                                    </div>
                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100">Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection