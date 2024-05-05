<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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

    public function store(Request $request)
    {
        $nik = Auth::user()->nik;
        $date_attendance = date("Y-m-d");
        $hour = date("H:i:s");
        $latitudekantor = -6.900171672847162;
        $longitudekantor = 107.60857690028557;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('date_attendance',$date_attendance)->where('nik',$nik)->count();

        if($cek > 0){
            $ket = "out";
        }else {
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/presensi/";
        $formatName = $nik . "-" . $date_attendance . "-" . $ket;
        $image_parts = explode(";base64",$image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        
        if($radius > 150){
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

    public function edit()
    {
        $nik = Auth::user()->nik;
        $karyawan = DB::table('users')->where('nik',$nik)->first();
        return view('presensi.editprofile',compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $nik = Auth::user()->nik;
        $name = $request->name;
        $email = $request->email;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('users')->where('nik', $nik)->first();
        if($request->hasFile('foto')){
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $karyawan->foto;
        }

        if(empty($request->password)){
            $data = [
                'name' => $name,
                'email' => $email,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        }else {
            $data = [
                'name' => $name,
                'email' => $email,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('users')->where('nik',$nik)->update($data);
        if($update){
            if($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update' ]);
        }else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }


    public function history()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.history',compact('namaBulan'));
    }

    public function gethistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::user()->nik;

        $history = DB::table('presensi')
            ->whereRaw('MONTH(date_attendance)="'. $bulan .'"')
            ->whereRaw('YEAR(date_attendance)="'. $tahun .'"')
            ->where('nik', $nik)
            ->orderBy('date_attendance')
            ->get();
        return view('presensi.gethistory',compact('history'));
    }

    public function izin()
    {
        $nik = Auth::user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik',$nik)->get();
        return view('presensi.izin',compact('dataizin'));
    }

    public function buatizin()
    {

        return view('presensi.buatizin');    
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::user()->nik;
        $date_izin = $request->date_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'date_izin' => $date_izin,
            'status' => $status,
            'keterangan' => $keterangan,
        ];

        $save = DB::table('pengajuan_izin')->insert($data);

        if($save){
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        }else{
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }
}
