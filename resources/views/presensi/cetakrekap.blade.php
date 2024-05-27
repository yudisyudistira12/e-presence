<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        @page { 
            size: F4 
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
            font-size: 10px;
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

<body class="F4 landscape">
    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td>
                    <img src="{{ asset('assets/img/logo_alfamart_transparent.png') }}" width="200" height="80" alt="">
                </td>
                <td>
                    <span id="title">
                        Rekap Presensi Karyawan <br>
                        Periode {{ $namaBulan[$bulan] }} {{ $tahun }} <br>
                        ALFAMART Cabang Bandung I <br>
                    </span>
                    <span><i>Jl. Moh. Toha 3, Bandung, Jawa Barat</i></span>
                </td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th rowspan="2">Nik</th>
                <th rowspan="2">Nama</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TT</th>
            </tr>
            <tr>
                <?php
                    for ($i=1; $i<32 ; $i++) { 
                ?>
                    <th>{{ $i }}</th>
                <?php
                }
                ?>
            </tr>
            @foreach ($rekap as $r)
                <tr>
                    <td>{{ $r->nik }}</td>
                    <td>{{ $r->name }}</td>
                    
                    <?php
                    $totalHadir = 0;
                    $totalTerlambat = 0;
                    for ($i=1; $i <= 31; $i++) { 
                        $property = "tgl_".$i;
                        if (empty($r->$property)) {
                            $hadir = ['',''];
                            $totalHadir += 0;
                        }else {
                            $hadir = explode("-",$r->$property);
                            $totalHadir += 1;

                            $shift1_start = "07:00:00";
                            $shift2_start = "14:00:00";


                            if ($hadir[0] <= $shift1_start || ($hadir[0] >= $shift2_start && $hadir[0] <= "23:59:59")) {
                                if ($hadir[0] > $shift1_start && $hadir[0] < $shift2_start) {
                                    $totalTerlambat += 1;
                                }

                                if ($hadir[0] > $shift2_start) {
                                    $totalTerlambat += 1;
                                }
                            }
                        }
                    ?>
                    <td>
                        @php
                            $shift1_start = "07:00:00";
                            $shift2_start = "14:00:00";

                            $time = $hadir[0];

                            $color = "";
                            if ($time > $shift1_start && $time < $shift2_start) {
                                $color = "red";
                            } elseif ($time > $shift2_start) {
                                $color = "red";
                            }
                        @endphp
                        <span style="color:{{ $color }}">{{ $time }}</span><br>
                        @php
                        // Periksa apakah array $hadir memiliki setidaknya dua elemen
                        if (isset($hadir[1])) {
                            // Waktu batas pulang untuk shift pertama dan kedua
                            $shift1_end = "15:00:00";
                            $shift2_end = "22:00:00";
                            // Ambil waktu pulang
                            $out_time = $hadir[1];

                            // Tentukan warna berdasarkan waktu pulang
                            $color = "";
                            if ($out_time < $shift1_end || ($out_time > $shift1_end && $out_time < $shift2_end)) {
                                $color = "red"; // Pulang sebelum waktu yang ditentukan
                            }
                        } else {
                            // Jika $hadir[1] tidak ada, set warna ke default (kosong)
                            $out_time = "";
                            $color = "";
                        }
                        @endphp

                        <span style="color:{{ $color }}">{{ $out_time }}</span>
                    </td>

                    <?php
                    }
                    ?>
                    <td>{{ $totalHadir }}</td>
                    <td>{{ $totalTerlambat }}</td>
                </tr>
                
            @endforeach
        </table>


        <table width="100%" style="margin-top:100px">
            <tr>
                <td style="text-align: right;">Bandung, {{ date("d-m-Y") }}</td>
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