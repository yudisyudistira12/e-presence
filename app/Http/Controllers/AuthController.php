<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function postlogin(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        $data = [
            'nik' => $request->nik,
            'password' => $request->password,
        ];

        if(Auth::attempt($data)){
            if(Auth::user()->role == 'Karyawan'){
                return redirect()->route('dashboard');
            } elseif (Auth::user()->role == 'Admin'){
                return redirect('/admin');
            }
        }else{
            return redirect()->route('login')->with('failed','Nik atau Password salah');
        }
    }

    public function admin()
    {
        echo "Test Admin";
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
