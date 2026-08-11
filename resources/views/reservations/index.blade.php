<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Reservas') }}
            </h2>
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Nueva Reserva
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Habitación</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Llegada / Salida</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Días</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Desc.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicios</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Costo Total (c/IVA)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción / Historial</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($reservations as $reservation)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap font-bold">{{ $reservation->room_type }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $reservation->check_in }} <br>
                                        <span class="text-xs text-gray-500">a {{ $reservation->check_out }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $reservation->dias_reserva }} n.</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($reservation->porcentaje_descuento > 0)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold">{{ $reservation->porcentaje_descuento }}%</span>
                                        @else
                                            <span class="text-gray-400">0%</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <ul class="list-disc list-inside text-xs">
                                            @forelse($reservation->services as $service)
                                                <li>{{ $service->nombre }} (${{ number_format($service->pivot->precio_en_reserva, 2) }})</li>
                                            @empty
                                                <span class="text-gray-400">Ninguno</span>
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap font-bold text-indigo-600">
                                        ${{ number_format($reservation->costo_total, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <form action="{{ route('reservations.updateEstado', $reservation) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="estado" onchange="this.form.submit()" class="text-xs rounded border-gray-300 p-1">
                                                <option value="pendiente" {{ $reservation->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="confirmado" {{ $reservation->estado == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                                <option value="cancelado" {{ $reservation->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                                <option value="completado" {{ $reservation->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-4 py-4 text-xs">
                                        <details class="cursor-pointer">
                                            <summary class="text-indigo-600 underline font-semibold">Ver Historial ({{ $reservation->registros->count() }})</summary>
                                            <div class="mt-2 p-2 bg-gray-50 border rounded space-y-1">
                                                @foreach($reservation->registros as $reg)
                                                    <div class="text-gray-600 border-b pb-1">
                                                        <span class="font-bold uppercase">{{ $reg->estado }}</span> 
                                                        por <span class="font-semibold">{{ $reg->user->name ?? 'Usuario' }}</span> 
                                                        <br>
                                                        <span class="text-[10px] text-gray-400">{{ $reg->fecha_cambio }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500">No tienes reservas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>