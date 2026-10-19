<?php

namespace App\Http\Controllers;

use App\Models\Booking; // Perbaikan: Gunakan bentuk tunggal & kapital
use App\Models\BookingDetail;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingsController extends Controller
{
    // Admin: Lihat semua daftar reservasi
    public function index()
    {
        $bookings = Booking::with('details.court')->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        //
    }

    // Pelanggan: Proses pembuatan booking
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date'   => 'required|date',
            'court_id'       => 'required|exists:courts,id',
            'start_time'     => 'required',
            'duration_hours' => 'required|integer|min:1',
        ]);

        // 1. Cek bentrok jam di tanggal & lapangan yang sama
        $isBooked = BookingDetail::where('court_id', $request->court_id)
            ->where('start_time', $request->start_time)
            ->whereHas('booking', function ($query) use ($request) {
                $query->where('booking_date', $request->booking_date)
                      ->where('status', '!=', 'Batal');
            })
            ->exists();

        if ($isBooked) {
            return redirect()->back()->with('error', 'Jadwal pada tanggal dan jam tersebut sudah terisi!');
        }

        // 2. Hitung harga (Perbaikan perkalian)
        $court = Court::findOrFail($request->court_id);
        $subtotal = $court->price_per_hour * $request->duration_hours;

        // 3. Simpan data ke tabel bookings & booking_details
        DB::transaction(function () use ($request, $subtotal) {
            $booking = Booking::create([
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date'   => $request->booking_date,
                'total_price'    => $subtotal,
                'status'         => 'Pending',
            ]);

            $booking->details()->create([
                'court_id'       => $request->court_id,
                'start_time'     => $request->start_time,
                'duration_hours' => $request->duration_hours,
                'subtotal'       => $subtotal,
            ]);
        });

        return redirect()->back()->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function show(Booking $booking)
    {
        //
    }

    public function edit(Booking $booking)
    {
        //
    }

    // Admin: Update status pembayaran
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Lunas,Batal',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        //
    }
}