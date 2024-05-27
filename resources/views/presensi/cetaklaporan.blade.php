<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Presensi {{ $karyawan->name }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        @page { 
            size: A4 
        }

        #title{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabledatakaryawan {
            margin-top: 40px;
        }

        .tabledatakaryawan,tr td {
            padding: 5px;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
        }

        .tabelpresensi tr th {
            border: 1px solid #131212;
            padding: 8px;
            background: #dbdbdb;
        }

        .tabelpresensi tr td {
            border: 1px solid #131212;
            padding: 8px;
            font-size: 12px;
        }

        .foto {
            width: 40px;
            height: 30px;
        }
    </style>
</head>

<body class="A4">
    <?php
        function selisih($in_hour, $out_hour)
        {
            list($h, $m, $s) = explode(":", $in_hour);
            $dtAwal = mktime($h, $m, $s, 1, 1, 1);
            list($h, $m, $s) = explode(":", $out_hour);
            $dtAkhir = mktime($h, $m, $s, 1, 1, 1);
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalMenit = $dtSelisih / 60;
            $jam = floor($totalMenit / 60);
            $sisaMenit = $totalMenit % 60;
            return $jam . ":" . round($sisaMenit);
        }
    ?>
    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td>
                    <img src="{{ asset('assets/img/logo_alfamart_transparent.png') }}" width="200" height="80" alt="">
                </td>
                <td>
                    <span id="title">
                        Laporan Presensi Karyawan <br>
                        Periode {{ $namaBulan[$bulan] }} {{ $tahun }} <br>
                        ALFAMART Cabang Bandung I <br>
                    </span>
                    <span><i>Jl. Moh. Toha 3, Bandung, Jawa Barat</i></span>
                </td>
            </tr>
        </table>
        <table class="tabledatakaryawan">
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/karyawan/'.$karyawan->foto)
                    @endphp
                        <img src="{{ url($path) }}" width="120" height="150" alt="">
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $karyawan->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $karyawan->email }}</td>
            </tr>
            <tr>
                <td>No HP</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->role }}</td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto Masuk</th>
                <th>Jam Pulang</th>
                <th>Foto Pulang</th>
                <th>Keterangan</th>
            </tr>
            @foreach ($presensi as $p)
                @php
                    $foto_in = Storage::url('uploads/presensi/'.$p->foto_in);
                    $foto_out = Storage::url('uploads/presensi/'.$p->foto_out);
                @endphp
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date("d-m-Y",strtotime($p->date_attendance)) }}</td>
                    <td>{{ $p->in_hour }}</td>
                    <td><img src="{{ url($foto_in) }}" class="foto" alt=""></td>
                    <td>{{ $p->out_hour != null ? $p->out_hour : 'Belum Pulang' }}</td>
                    <td>
                        @if ($p->out_hour != null)
                            <img src="{{ url($foto_out) }}" class="foto" alt="">
                        @else
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-photo-off"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M7 3h11a3 3 0 0 1 3 3v11m-.856 3.099a2.991 2.991 0 0 1 -2.144 .901h-12a3 3 0 0 1 -3 -3v-12c0 -.845 .349 -1.608 .91 -2.153" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M16.33 12.338c.574 -.054 1.155 .166 1.67 .662l3 3" /><path d="M3 3l18 18" /></svg>
                        @endif
                    </td>
                    <td>
                        @if ($p->in_hour <= '07:00:00')
                            <span class="badge text-bg-success">Tepat Waktu</span>
                        @elseif ($p->in_hour > '07:00:00' && $p->in_hour <= '07:15:00')
                        @php
                            $jamTerlambat = selisih('07:00:00', $p->in_hour);
                        @endphp
                            <span class="badge text-bg-danger">Terlambat {{ $jamTerlambat }}</span>
                        @elseif ($p->in_hour > '07:15:00' && $p->in_hour < '12:00:00')
                            <span class="badge text-bg-danger">Tidak Masuk</span>
                        @elseif ($p->in_hour >= '14:00:00' && $p->in_hour <= '14:15:00')
                        @php
                            $jamTerlambat = selisih('14:00:00', $p->in_hour);
                        @endphp
                        <span class="badge text-bg-danger">Terlambat {{ $jamTerlambat }}</span>
                            @elseif ($p->in_hour > '14:15:00')
                        <span class="badge text-bg-danger">Tidak Masuk</span>
                        @else
                            <span class="badge text-bg-warning">Tidak dapat menentukan shift</span>
                        @endif
                    </td>
            @endforeach
        </table>
        <table width="100%" style="margin-top:100px">
            <tr>
                <td colspan="2" style="text-align: right">Bandung, {{ date("d-m-Y") }}</td>
            </tr>
            <tr>
                <td style="text-align:right;vertical-align:bottom" height="100px">
                    <u>Anri Firmansyah</u>
                    <br>
                    <i><b>Kepala Toko</b></i>
                </td>
            </tr>
        </table>

    </section>

</body>
</html>