<x-app-layout>
    <x-slot name="header">
        <div class="w-full text-left">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Reservas — Hotel Paraíso del Bosque
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium">Lista de reservas</h3>
                <a href="{{ route('reservations.create') }}"
                class="px-4 py-2 bg-blue-600 text-gray-900 font-medium rounded shadow hover:bg-blue-700">
                    + Nueva reserva
                </a>
            </div>

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Llegada</th>
                            <th class="px-4 py-2 text-left">Salida</th>
                            <th class="px-4 py-2 text-left">Huéspedes</th>
                            <th class="px-4 py-2 text-left">Habitación</th>
                            <th class="px-4 py-2 text-left">Comentarios</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $reservation->fecha_llegada->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $reservation->fecha_salida->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $reservation->numero_huespedes }}</td>
                                <td class="px-4 py-2 capitalize">{{ $reservation->tipo_habitacion }}</td>
                                <td class="px-4 py-2">{{ $reservation->comentarios ?: '—' }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta reserva?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Aún no tienes reservas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Punto 8: mostrar los últimos IDs de reservas guardados en cookie --}}
            <div class="mt-6 text-sm text-gray-500">
                <strong>Últimas reservas creadas (cookie):</strong>
                @if (count($ultimosIds))
                    {{ implode(', ', array_map(fn($id) => '#' . $id, $ultimosIds)) }}
                @else
                    Sin datos aún.
                @endif
            </div>

        </div>
    </div>
</x-app-layout>