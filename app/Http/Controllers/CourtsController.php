<?php

namespace App\Http\Controllers;

use App\Models\Court; // Perbaikan: Gunakan bentuk tunggal & kapital
use Illuminate\Http\Request;

class CourtsController extends Controller
{
    // Menampilkan semua lapangan di halaman utama pelanggan
    public function index()
    {
        $courts = Court::all();
        return view('welcome', compact('courts'));
    }

    public function create()
    {
        //
    }

    // Admin: Simpan data lapangan baru beserta gambarnya
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:Futsal,Badminton,Basket',
            'price_per_hour' => 'required|numeric',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courts', 'public');
        }

        Court::create([
            'name'           => $request->name,
            'type'           => $request->type,
            'price_per_hour' => $request->price_per_hour,
            'description'    => $request->description,
            'image'          => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function show(Court $court)
    {
        //
    }

    public function edit(Court $court)
    {
        //
    }

    public function update(Request $request, Court $court)
    {
        //
    }

    public function destroy(Court $court)
    {
        //
    }
}