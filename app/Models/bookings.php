<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bookings extends Model
{
    //Mengizinkan semua kolom diisi data, kecuali kolom 'id'
    protected $guarded = ['id'];

    //Relasi: 1 Nota/Booking punya BANYAK item detail rincian
    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
