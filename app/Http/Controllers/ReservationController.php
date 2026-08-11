<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ReservationController extends Controller
{
    /**
     * Muestra todas las reservas del usuario autenticado.
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->orderBy('fecha_llegada', 'desc')
            ->get();

        $ultimosIds = json_decode(Cookie::get('ultimas_reservas', '[]'), true);

        return view('reservations.index', compact('reservations', 'ultimosIds'));
    }

    /**
     * Muestra el formulario para crear una reserva.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Guarda una nueva reserva y actualiza la cookie de últimos IDs.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_llegada'     => 'required|date|after_or_equal:today',
            'fecha_salida'      => 'required|date|after:fecha_llegada',
            'numero_huespedes'  => 'required|integer|min:1|max:20',
            'tipo_habitacion'   => 'required|string|in:sencilla,doble,suite,familiar',
            'comentarios'       => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        $reservation = Reservation::create($validated);

        $this->guardarCookieUltimaReserva($reservation->id);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva creada correctamente.');
    }

    /**
     * Elimina una reserva (solo si pertenece al usuario autenticado).
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva eliminada.');
    }

    /**
     * Guarda en cookie los últimos IDs de reservas creadas (máximo 5).
     */
    private function guardarCookieUltimaReserva(int $id): void
    {
        $ultimos = json_decode(Cookie::get('ultimas_reservas', '[]'), true);

        array_unshift($ultimos, $id);
        $ultimos = array_slice($ultimos, 0, 5);

        Cookie::queue('ultimas_reservas', json_encode($ultimos), 60 * 24 * 30);
    }
}