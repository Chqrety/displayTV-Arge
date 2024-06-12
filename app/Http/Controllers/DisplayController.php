<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class DisplayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // URL API
        $baseUrl = URL::to('/');
        $messages = [
            'Welcome to our website!',
            'Latest news: Laravel 11 is released!',
            'Check out our new features!'
        ];

        return view('pages.display.index', compact('messages', 'baseUrl'));
    }

    public function data(Request $request)
    {
        // Mendekode data yang diterima dari request
        $decodedData = json_decode($request->getContent(), true);

        // Ambil data dari cache
        $cachedData = Cache::get('antrian_data', []);

        // Jika data dalam format JSON, tambahkan data baru ke cache
        if (is_string($cachedData)) {
            $cachedData = json_decode($cachedData, true);
        }

        // Tambahkan data baru ke awal array
        array_unshift($cachedData, $decodedData);

        // Batasi jumlah data agar tidak lebih dari lima
        $cachedData = array_slice($cachedData, 0, 5);

        // Simpan data yang telah diperbarui ke dalam cache
        Cache::put('antrian_data', $cachedData);

        return response()->json(['message' => 'Data received and cached.']);
    }

    public function getData()
    {
        // Ambil data dari cache
        $cachedData = Cache::get('antrian_data', []);

        return response()->json($cachedData);
    }

    public function deleteData()
    {
        // Lakukan logika untuk menghapus data
        Cache::forget('antrian_data');

        // Berikan respons sesuai kebutuhan, misalnya:
        return response()->json(['message' => 'Data deleted successfully']);
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
