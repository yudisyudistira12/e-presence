<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = date("Y-m-d");
        $thisMonth = date("m") * 1;
        $thisYear = date("Y");
        $nik = Auth::user()->nik;
        $presensiToday = DB::table('presensi')->where('nik', $nik)->where('date_attendance', $today)->first();
        $historyThisMonth = DB::table('presensi')
            ->where('nik',$nik)
            ->whereRaw('MONTH(date_attendance)="' . $thisMonth . '"')
            ->whereRaw('YEAR(date_attendance)="' . $thisYear . '"')
            ->orderBy('date_attendance')
            ->get();
        $rekapPresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(in_hour > "07:00",1,0)) as jmlterlambat')
            ->where('nik',$nik)
            ->whereRaw('MONTH(date_attendance)="' . $thisMonth . '"')
            ->whereRaw('YEAR(date_attendance)="' . $thisYear . '"')
            ->first();
        
        $nameMonth = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('dashboard.dashboard',compact('presensiToday','historyThisMonth','nameMonth','thisMonth','thisYear','rekapPresensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
