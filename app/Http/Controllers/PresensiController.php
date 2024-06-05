<?php

namespace App\Http\Controllers;

use App\Exports\ExportPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;
use App\Exports\RekapPresensiExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

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
        $loc_office = DB::table('configuration_office')->where('id',1)->first();
        return view('presensi.create',compact('cek','loc_office'));
    }

    public function store(Request $request)
    {
        $nik = Auth::user()->nik;
        $date_attendance = date("Y-m-d");
        $hour = date("H:i:s");
        $loc_office = DB::table('configuration_office')->where('id',1)->first();
        $loc = explode(",", $loc_office->location_office);
        $latitudekantor = $loc[0];
        $longitudekantor = $loc[1];
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

        
        if($radius > $loc_office->radius){
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
    
        // Menambahkan nilai default untuk kolom 'status_approved'
        $status_approved = 0; // atau sesuai dengan kebutuhan Anda
    
        $data = [
            'nik' => $nik,
            'date_izin' => $date_izin,
            'status' => $status,
            'keterangan' => $keterangan,
            'status_approved' => $status_approved, // Menambahkan nilai untuk kolom 'status_approved'
        ];
    
        $save = DB::table('pengajuan_izin')->insert($data);
    
        if($save){
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        }else{
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }
    

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->join('users', 'presensi.nik', '=', 'users.nik')
            ->select('presensi.*', 'users.name')
            ->where('date_attendance', $tanggal)
            ->get();
        
        return view('presensi.getpresensi',compact('presensi'));
    }

    public function showmap(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
            ->join('users', 'presensi.nik', '=', 'users.nik')
            ->where('presensi.id', $id)
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('users')->orderBy('name')->get();
        return view('presensi.laporan',compact('namaBulan','karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawan = DB::table('users')->where('nik', $nik)->first();
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $presensi = DB::table('presensi')
            ->where('nik',$nik)
            ->whereRaw('MONTH(date_attendance)="'. $bulan .'"')
            ->whereRaw('YEAR(date_attendance)="'. $tahun .'"')
            ->orderBy('date_attendance')
            ->get();

        return view('presensi.cetaklaporan',compact('namaBulan', 'bulan', 'tahun', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap',compact('namaBulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
            ->selectRaw('presensi.nik,name,
            MAX(IF(DAY(date_attendance) = 1, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_1,
            MAX(IF(DAY(date_attendance) = 2, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_2,
            MAX(IF(DAY(date_attendance) = 3, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_3,
            MAX(IF(DAY(date_attendance) = 4, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_4,
            MAX(IF(DAY(date_attendance) = 5, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_5,
            MAX(IF(DAY(date_attendance) = 6, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_6,
            MAX(IF(DAY(date_attendance) = 7, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_7,
            MAX(IF(DAY(date_attendance) = 8, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_8,
            MAX(IF(DAY(date_attendance) = 9, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_9,
            MAX(IF(DAY(date_attendance) = 10, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_10,
            MAX(IF(DAY(date_attendance) = 11, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_11,
            MAX(IF(DAY(date_attendance) = 12, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_12,
            MAX(IF(DAY(date_attendance) = 13, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_13,
            MAX(IF(DAY(date_attendance) = 14, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_14,
            MAX(IF(DAY(date_attendance) = 15, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_15,
            MAX(IF(DAY(date_attendance) = 16, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_16,
            MAX(IF(DAY(date_attendance) = 17, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_17,
            MAX(IF(DAY(date_attendance) = 18, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_18,
            MAX(IF(DAY(date_attendance) = 19, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_19,
            MAX(IF(DAY(date_attendance) = 20, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_20,
            MAX(IF(DAY(date_attendance) = 21, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_21,
            MAX(IF(DAY(date_attendance) = 22, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_22,
            MAX(IF(DAY(date_attendance) = 23, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_23,
            MAX(IF(DAY(date_attendance) = 24, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_24,
            MAX(IF(DAY(date_attendance) = 25, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_25,
            MAX(IF(DAY(date_attendance) = 26, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_26,
            MAX(IF(DAY(date_attendance) = 27, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_27,
            MAX(IF(DAY(date_attendance) = 28, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_28,
            MAX(IF(DAY(date_attendance) = 29, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_29,
            MAX(IF(DAY(date_attendance) = 30, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_30,
            MAX(IF(DAY(date_attendance) = 31, CONCAT(in_hour,"_",IFNULL(out_hour,"00:00:00")),"")) as tgl_31')
            ->join('users','presensi.nik','=','users.nik')
            ->whereRaw('MONTH(date_attendance)="'. $bulan .'"')
            ->whereRaw('YEAR(date_attendance)="'. $tahun .'"')
            ->groupByRaw('presensi.nik,name')
            ->get();
            
            if ($request->has('exportexcel')) {
                $filename = "Rekap_Presensi_Karyawan_$tahun-$bulan.xlsx";
        
                // Export data menggunakan class RekapPresensiExport
                return Excel::download(new ExportPresensi($bulan, $tahun), $filename);
            }

            return view('presensi.cetakrekap',compact('bulan','tahun','namaBulan','rekap'));
    }

    public function izinsakit()
    {
        $izinsakit = DB::table('pengajuan_izin')
        ->join('users','pengajuan_izin.nik', '=', 'users.nik')
        ->orderBy('date_izin','desc')
        ->get();
        return view('presensi.izinsakit',compact('izinsakit'));
    }
    
    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izin')->where('id',$id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function batalizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id',$id)->update([
            'status_approved' => 0
        ]);
        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }
}
