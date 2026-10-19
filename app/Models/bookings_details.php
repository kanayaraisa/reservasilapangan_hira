<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bookings_details extends Model
{
    //Beritahu Laravel nama tabel sebenarnya di database 
    protected $table = 'booking_details';

    //Mengizinkan semua kolom diisi data, kcuali kolom 'id'
    protected $guarded = ['id'];

    //relasi: 1 baris rincian ini MILIK dari satu nota booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    //Relasi: 1 baris rincian ini MILIK DARI 1 lapangan tertentu
    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
