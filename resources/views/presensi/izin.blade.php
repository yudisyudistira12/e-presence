@extends('layouts.presensi')
@section('title','Data Izin dan Sakit')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data izin dan Sakit</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        @php
            $messageSuccess = Session::get('success');
            $messageError = Session::get('error');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ $messageSuccess }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-error">
                {{ $messageError }}
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col">
        @foreach ($dataizin as $di )
            <ul class="listview image-listview">
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <b>{{ date("d-m-Y",strtotime($di->date_izin)) }} - {{ $di->status == "s" ? "Sakit" : "izin" }}</b>
                                <br>
                                <small class="text-muted">{{ $di->keterangan }}</small>
                            </div>
                            @if ($di->status_approved == 0)
                            <span class="badge bg-warning">Menunggu</span>
                            @elseif ($di->status_approved == 1)
                            <span class="badge bg-success">Disetujui</span>
                            @elseif ($di->status_approved == 2)
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        @endforeach
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@endsection