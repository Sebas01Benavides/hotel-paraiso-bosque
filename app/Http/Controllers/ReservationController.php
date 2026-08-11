<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\RegistroReserva;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['services', 'registros.user'])
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $services = Service::where('esta_activo', true)->get();
        return view('reservations.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'room_type' => 'required|string',
            'comments' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $diasReserva = $checkIn->diffInDays($checkOut);

        $preciosHabitacion = [
            'Sencilla' => 50,
            'Doble' => 80,
            'Suite' => 120,
            'Familiar' => 150,
        ];

        $precioNoche = $preciosHabitacion[$request->room_type] ?? 50;
        $subtotalHabitacion = $diasReserva * $precioNoche;

        $porcentajeDescuento = 0;
        if ($diasReserva > 7) {
            $porcentajeDescuento = 10;
            $subtotalHabitacion -= ($subtotalHabitacion * 0.10);
        }

        $costoServicios = 0;
        $serviciosSeleccionados = [];

        if ($request->has('services')) {
            $services = Service::whereIn('id', $request->services)->get();
            foreach ($services as $service) {
                $costoServicios += $service->precio;
                $serviciosSeleccionados[$service->id] = ['precio_en_reserva' => $service->precio];
            }
        }

        $subtotal = $subtotalHabitacion + $costoServicios;
        $iva = $subtotal * 0.13;
        $costoTotal = $subtotal + $iva;

        $reservation = Reservation::create([
            'user_id' => auth()->user()->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'room_type' => $request->room_type,
            'comments' => $request->comments,
            'dias_reserva' => $diasReserva,
            'porcentaje_descuento' => $porcentajeDescuento,
            'estado' => 'pendiente',
            'costo_total' => $costoTotal,
        ]);

        if (!empty($serviciosSeleccionados)) {
            $reservation->services()->attach($serviciosSeleccionados);
        }

        RegistroReserva::create([
            'reservation_id' => $reservation->id,
            'user_id' => auth()->user()->id,
            'estado' => 'pendiente',
            'fecha_cambio' => now(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente.');
    }
}