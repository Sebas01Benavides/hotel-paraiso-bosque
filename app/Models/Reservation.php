<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_llegada',
        'fecha_salida',
        'numero_huespedes',
        'tipo_habitacion',
        'comentarios',
    ];

    protected $casts = [
        'fecha_llegada' => 'date',
        'fecha_salida' => 'date',
    ];

    // Cada reserva pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}