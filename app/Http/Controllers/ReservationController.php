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
        $reservations = Reservation::with(['services', 'registros.user'])->where('user_id', auth()->id())->get();
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
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        // 1. Días de reserva
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $diasReserva = $checkIn->diffInDays($checkOut);

        // Tarifas base por habitación
        $preciosHabitacion = [
            'Standard' => 50,
            'Deluxe' => 80,
            'Suite' => 120,
        ];
        $precioNoche = $preciosHabitacion[$request->room_type] ?? 50;
        $subtotalHabitacion = $diasReserva * $precioNoche;

        // 2. Descuento si supera 7 días (10%)
        $porcentajeDescuento = 0;
        if ($diasReserva > 7) {
            $porcentajeDescuento = 10;
            $subtotalHabitacion -= ($subtotalHabitacion * 0.10);
        }

        // 3. Servicios adicionales
        $costoServicios = 0;
        $serviciosPivot = [];
        if ($request->has('services')) {
            $serviciosDb = Service::whereIn('id', $request->services)->get();
            foreach ($serviciosDb as $serv) {
                $costoServicios += $serv->precio;
                $serviciosPivot[$serv->id] = ['precio_en_reserva' => $serv->precio];
            }
        }

        // 4. IVA (13%) y costo total
        $subtotal = $subtotalHabitacion + $costoServicios;
        $iva = $subtotal * 0.13;
        $costoTotal = $subtotal + $iva;

        // 5. Crear reserva
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
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

        if (!empty($serviciosPivot)) {
            $reservation->services()->attach($serviciosPivot);
        }

        // Registrar en historial
        RegistroReserva::create([
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
            'estado' => 'pendiente',
            'fecha_cambio' => now(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente.');
    }

    public function updateEstado(Request $request, Reservation $reservation)
    {
        $request->validate(['estado' => 'required|in:pendiente,confirmado,cancelado,completado']);

        $reservation->update(['estado' => $request->estado]);

        RegistroReserva::create([
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
            'estado' => $request->estado,
            'fecha_cambio' => now(),
        ]);

        return back()->with('success', 'Estado actualizado.');
    }
}