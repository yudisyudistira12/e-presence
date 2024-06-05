<?php

namespace App\Http\Controllers;

use App\Models\OfficeSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ConfigureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loc_office = DB::table('configuration_office')->where('id',1)->first();
        return view('configuration.officesite',compact('loc_office'));
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
            'location_office' => 'required|max:100',
            'radius' => 'required|max:100',
        ]);

        $office = new OfficeSite();
        $office->nik = $request->nik;
        $office->name = $request->name;
        $office->save();

        dd($office);
        
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $location_office = $request->location_office;
        $radius = $request->radius;

        $update = DB::table('configuration_office')->where('id',1)->update([
            'location_office' => $location_office,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
