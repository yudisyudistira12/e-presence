<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = date("Y-m-d");
        $nik = Auth::user()->nik;
        $cek = DB::table('presensi')->where('date_attendance',$today)->where('nik',$nik)->count();
        return view('presensi.create',compact('cek'));
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
        $nik = Auth::user()->nik;
        $date_attendance = date("Y-m-d");
        $hour = date("H:i:s");
        $latitudekantor = -6.916623360034294;
        $longitudekantor = 107.61218364076126;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
        $image = $request->image;
        $folderPath = "public/uploads/presensi/";
        $formatName = $nik . "-" . $date_attendance;
        $image_parts = explode(";base64",$image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $cek = DB::table('presensi')->where('date_attendance',$date_attendance)->where('nik',$nik)->count();
        if($radius > 100){
            echo "error|Maaf Anda Berada Diluar Radius. Jarak Anda " . $radius . "meter dari Kantor|";
        }else{

            if($cek > 0){
                $data_pulang = [
                    'out_hour' => $hour,
                    'foto_out' => $fileName,
                    'location_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('date_attendance',$date_attendance)->where('nik',$nik)->update($data_pulang);
                if($update){
                    echo "success|Waktunya pulang, Hati Hati Di Jalan|out";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Gagal Presensi Pulang, Hubungi Admin IT|out";
                }
            }else{
                $data = [
                    'nik' => $nik,
                    'date_attendance' => $date_attendance,
                    'in_hour' => $hour,
                    'foto_in' => $fileName,
                    'location_in' => $lokasi
                ];
                $save = DB::table('presensi')->insert($data);
                if($save){
                    echo "success|Selamat Bekerja|in";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Gagal Presensi Masuk, Hubungi Admin IT|in";
                }
            }
        }

    }

    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
