<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPresensi implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return DB::table('presensi')
            ->selectRaw('
                presensi.nik, 
                users.name,
                MAX(IF(DAY(date_attendance) = 1, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_1,
                MAX(IF(DAY(date_attendance) = 2, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_2,
                MAX(IF(DAY(date_attendance) = 3, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_3,
                MAX(IF(DAY(date_attendance) = 4, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_4,
                MAX(IF(DAY(date_attendance) = 5, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_5,
                MAX(IF(DAY(date_attendance) = 6, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_6,
                MAX(IF(DAY(date_attendance) = 7, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_7,
                MAX(IF(DAY(date_attendance) = 8, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_8,
                MAX(IF(DAY(date_attendance) = 9, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_9,
                MAX(IF(DAY(date_attendance) = 10, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_10,
                MAX(IF(DAY(date_attendance) = 11, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_11,
                MAX(IF(DAY(date_attendance) = 12, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_12,
                MAX(IF(DAY(date_attendance) = 13, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_13,
                MAX(IF(DAY(date_attendance) = 14, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_14,
                MAX(IF(DAY(date_attendance) = 15, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_15,
                MAX(IF(DAY(date_attendance) = 16, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_16,
                MAX(IF(DAY(date_attendance) = 17, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_17,
                MAX(IF(DAY(date_attendance) = 18, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_18,
                MAX(IF(DAY(date_attendance) = 19, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_19,
                MAX(IF(DAY(date_attendance) = 20, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_20,
                MAX(IF(DAY(date_attendance) = 21, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_21,
                MAX(IF(DAY(date_attendance) = 22, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_22,
                MAX(IF(DAY(date_attendance) = 23, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_23,
                MAX(IF(DAY(date_attendance) = 24, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_24,
                MAX(IF(DAY(date_attendance) = 25, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_25,
                MAX(IF(DAY(date_attendance) = 26, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_26,
                MAX(IF(DAY(date_attendance) = 27, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_27,
                MAX(IF(DAY(date_attendance) = 28, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_28,
                MAX(IF(DAY(date_attendance) = 29, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_29,
                MAX(IF(DAY(date_attendance) = 30, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_30,
                MAX(IF(DAY(date_attendance) = 31, CONCAT(in_hour, "_", IFNULL(out_hour, "00:00:00")), "")) as tgl_31
            ')
            ->join('users', 'presensi.nik', '=', 'users.nik')
            ->whereRaw('MONTH(date_attendance) = ?', [$this->bulan])
            ->whereRaw('YEAR(date_attendance) = ?', [$this->tahun])
            ->groupByRaw('presensi.nik, users.name')
            ->get();
    }
    

    public function headings(): array
    {
        return [
            'NIK', 'Name', 'Tgl 1', 'Tgl 2', 'Tgl 3', 'Tgl 4', 'Tgl 5', 'Tgl 6', 
            'Tgl 7', 'Tgl 8', 'Tgl 9', 'Tgl 10', 'Tgl 11', 'Tgl 12', 'Tgl 13', 
            'Tgl 14', 'Tgl 15', 'Tgl 16', 'Tgl 17', 'Tgl 18', 'Tgl 19', 'Tgl 20', 
            'Tgl 21', 'Tgl 22', 'Tgl 23', 'Tgl 24', 'Tgl 25', 'Tgl 26', 'Tgl 27', 
            'Tgl 28', 'Tgl 29', 'Tgl 30', 'Tgl 31'
        ];
    }
}
