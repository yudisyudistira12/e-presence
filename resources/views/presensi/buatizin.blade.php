@extends('layouts.presensi')
@section('title','Form Pengajuan Izin')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }
    .datepicker-date-display{
        background-color: #0f3a7e !important;
    }
</style>
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Pengajuan Izin</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form action="/presensi/storeizin" method="POST" id="frmIzin">
            @csrf
            <div class="form-group">
                <input type="text" name="date_izin" id="date_izin" class="form-control datepicker" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="" selected disabled>Izin / Sakit</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

$(document).ready(function() {
  $(".datepicker").datepicker({
    format: "yyyy/mm/dd"    
  });
  $("#frmIzin").submit(function(){
    var date_izin = $("#date_izin").val();
    var status = $("#status").val();
    var keterangan = $("#keterangan").val();
    if(date_izin == ""){
        Swal.fire({
            title: 'Oops !',
            text: 'Tanggal Harus Diisi',
            icon: 'warning'
        });
    } else if(status == ""){
        Swal.fire({
            title: 'Oops !',
            text: 'Status Harus Diisi',
            icon: 'warning'
        });
    } else if(keterangan == ""){
        Swal.fire({
            title: 'Oops !',
            text: 'Keterangan Harus Diisi',
            icon: 'warning'
        });
    }
  });
});

</script>
@endpush