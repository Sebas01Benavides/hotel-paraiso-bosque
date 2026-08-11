<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Service::create([
            'nombre' => 'Limpieza Extra',
            'descripcion' => 'Servicio diario de limpieza profunda de la habitación',
            'precio' => 15.00,
            'esta_activo' => true,
        ]);

        Service::create([
            'nombre' => 'Desayuno Buffet',
            'descripcion' => 'Acceso al comedor principal con menú buffet variado',
            'precio' => 10.00,
            'esta_activo' => true,
        ]);

        Service::create([
            'nombre' => 'Transporte Aeropuerto',
            'descripcion' => 'Traslado privado directo desde o hacia el aeropuerto',
            'precio' => 25.00,
            'esta_activo' => true,
        ]);
    }
}