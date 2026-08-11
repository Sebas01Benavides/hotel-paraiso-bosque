<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'guests',
        'room_type',
        'comments',
        'dias_reserva',
        'porcentaje_descuento',
        'estado',
        'costo_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'reservation_service')
                    ->withPivot('precio_en_reserva')
                    ->withTimestamps();
    }

    public function registros()
    {
        return $this->hasMany(RegistroReserva::class);
    }
}