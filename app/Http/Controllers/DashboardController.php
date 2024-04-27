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
        $thisMonth = date("m");
        $thisYear = date("Y");
        $nik = Auth::user()->nik;
        $presensiToday = DB::table('presensi')->where('nik', $nik)->where('date_attendance', $today)->first();
        $historyThisMonth = DB::table('presensi')->whereRaw('MONTH(date_attendance)="' . $thisMonth . '"')
            ->whereRaw('YEAR(date_attendance)="' . $thisYear . '"')
            ->get();
        return view('dashboard.dashboard',compact('presensiToday','historyThisMonth'));
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
