<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.antrian.index');
    }

    public function data()
    {
        // Data nomor antrian dan poli
        $data = Antrian::select('no_antrian', 'no_poli', 'no_antrian_rm')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

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
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'no_antrian' => 'nullable|string|max:255',
            'no_poli' => 'nullable|integer|max:255',
            'no_antrian_rm' => 'nullable|string|max:255',
        ]);

        // Simpan data yang diterima dari formulir ke dalam database atau tempat penyimpanan lainnya
        // Misalnya:
        $antrian = new Antrian();
        $antrian->no_antrian = $validatedData['no_antrian'] ?? null;
        $antrian->no_poli = $validatedData['no_poli'] ?? null;
        $antrian->no_antrian_rm = $validatedData['no_antrian_rm'] ?? null;
        $antrian->save();

        // Beri respons JSON untuk memberi tahu klien bahwa data telah berhasil disimpan
        return response()->json(['message' => 'Data stored successfully', 'data' => $validatedData]);
    }
    private function generateRandomAntrian()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetter = $letters[rand(0, strlen($letters) - 1)];
        $randomNumber = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
        return "{$randomLetter}.{$randomNumber}";
    }

    private function generateRandomPoli()
    {
        return rand(1, 9);
    }
    public function generate(Request $request)
    {
        // Generate nomor antrian dan nomor poli
        $no_antrian = $this->generateRandomAntrian();
        $no_poli = $this->generateRandomPoli();

        // Simpan data ke dalam database
        $antrian = new Antrian();
        $antrian->no_antrian = $no_antrian;
        $antrian->no_poli = $no_poli;
        $antrian->save();

        // Kembalikan response JSON
        return response()->json([
            'nomor_antrian' => $no_antrian,
            'poli' => $no_poli
        ]);
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
