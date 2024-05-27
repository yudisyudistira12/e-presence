<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('users')->orderBy('name');
        
        // Cek apakah ada parameter pencarian 'name'
        if ($request->has('name')) {
            $name = $request->input('name');
            $query->where('name', 'like', '%' . $name . '%');
        }
    
        // Dapatkan hasil pencarian dengan paginasi
        $karyawan = $query->paginate(5);
    
        return view('Karyawan.index', compact('karyawan'));
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
        $validatedData = $request->validate([
            'nik' => 'required|unique:users|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'no_hp' => 'required|max:15',
            'role' => 'required|max:50',
        ]);

        $karyawan = new User;
        $karyawan->nik = $request->nik;
        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->no_hp = $request->no_hp;
        $karyawan->role = $request->role;
        $karyawan->password = Hash::make('karyawan123');
        $karyawan->save();
        
        return Redirect::back()->with('success', 'User berhasil disimpan.');
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
    public function edit($id)
    {
        $karyawan = DB::table('users')->where('id',$id)->first();
        return view('Karyawan.edit',compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $nik = $request->nik;
        $name = $request->name;
        $email = $request->email;
        $no_hp = $request->no_hp;
            $data = [
                'nik' => $nik,
                'name' => $name,
                'email' => $email,
                'no_hp' => $no_hp
            ];

        $update = DB::table('users')->where('nik',$nik)->update($data);
        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update' ]);
        }else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */

     public function destroy($id)
     {
        $data = DB::table('users')->where('id',$id)->delete();
        return redirect('/karyawan')->with('success','Data Berhasil Dihapus');
     }
}
