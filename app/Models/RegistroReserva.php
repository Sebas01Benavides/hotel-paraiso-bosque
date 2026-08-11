<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroReserva extends Model
{
    protected $table = 'registro_reservas';

    protected $fillable = ['reservation_id', 'user_id', 'estado', 'fecha_cambio'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}