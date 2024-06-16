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
        // Mendapatkan data yang diterima dari permintaan
        $receivedData = $request->all();

        // Dekode data jika data dalam format JSON
        if (is_string($receivedData)) {
            $decodedData = json_decode($receivedData, true);
            return response()->json($decodedData);
        }

        // Ambil data dari cache
        $cachedData = Cache::get('antrian_data', []);

        // Periksa apakah data cache sudah mencapai lima
        if (count($cachedData) >= 5) {
            // Jika sudah lima, hapus data terakhir
            array_pop($cachedData);
        }

        // Tambahkan data baru ke awal array
        array_unshift($cachedData, $receivedData);

        // Simpan data yang telah diperbarui ke dalam cache
        Cache::put('antrian_data', $cachedData);

        return response()->json(['message' => 'Data received and cached.']);
    }

    public function getData()
    {
        // Ambil data dari cache
        $cachedData = Cache::get('antrian_data', []);

        // Dekode data jika data dalam format JSON
        if (is_string($cachedData)) {
            $decodedData = json_decode($cachedData, true);
            return response()->json($decodedData);
        }

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
