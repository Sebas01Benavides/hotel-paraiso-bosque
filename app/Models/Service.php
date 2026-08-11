<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'esta_activo'];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_service')
                    ->withPivot('precio_en_reserva')
                    ->withTimestamps();
    }
}