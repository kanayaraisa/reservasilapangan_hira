<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courts extends Model
{
    //Mengizinkan semua kolom diisi data, kecuali kolom 'id'
    protected $guarded =['id'];

    //Relasi: 1 lapangan bisa ada di BANYAK transaksi detail
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
