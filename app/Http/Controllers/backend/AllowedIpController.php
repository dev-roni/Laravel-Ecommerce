<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AllowedIp;
use Illuminate\Http\Request;

class AllowedIpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowedIps = AllowedIp::all();
        return view('backend.pages.settings',compact('allowedIps'));
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
    public function show(AllowedIp $allowedIp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AllowedIp $allowedIp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AllowedIp $allowedIp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AllowedIp $allowedIp)
    {
        //
    }
}
