<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Data nomor antrian dan poli
        $data = [
            [
                'nomor_antrian' => 'C.24',
                'poli' => 'Poli 3'
            ],
            [
                'nomor_antrian' => 'D.56',
                'poli' => 'Poli 4'
            ],
            [
                'nomor_antrian' => 'H.76',
                'poli' => 'Poli 8'
            ]
        ];

        // Mengembalikan response JSON
        return response()->json($data);
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
