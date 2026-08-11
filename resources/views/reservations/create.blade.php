<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <!-- Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="check_in" value="Fecha de Llegada" />
                            <x-text-input id="check_in" name="check_in" type="date" class="mt-1 block w-full" :value="old('check_in')" required />
                        </div>
                        <div>
                            <x-input-label for="check_out" value="Fecha de Salida" />
                            <x-text-input id="check_out" name="check_out" type="date" class="mt-1 block w-full" :value="old('check_out')" required />
                        </div>
                    </div>

                    <!-- Huéspedes y Habitación -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="guests" value="Número de Huéspedes" />
                            <x-text-input id="guests" name="guests" type="number" min="1" class="mt-1 block w-full" :value="old('guests')" required />
                        </div>
                        <div>
                            <x-input-label for="room_type" value="Tipo de Habitación" />
                            <select id="room_type" name="room_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="Standard">Standard ($50 / noche)</option>
                                <option value="Deluxe">Deluxe ($80 / noche)</option>
                                <option value="Suite">Suite ($120 / noche)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Servicios Adicionales -->
                    <div class="mb-4">
                        <x-input-label value="Servicios Adicionales" class="mb-2" />
                        <div class="space-y-2 border rounded p-4 bg-gray-50">
                            @forelse($services as $service)
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" id="service_{{ $service->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                                    <label for="service_{{ $service->id }}" class="text-sm text-gray-700">
                                        <span class="font-semibold">{{ $service->nombre }}</span> 
                                        (${{ number_format($service->precio, 2) }})
                                        @if($service->descripcion)
                                            - <span class="text-gray-500">{{ $service->descripcion }}</span>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No hay servicios adicionales disponibles.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="mb-6">
                        <x-input-label for="comments" value="Comentarios Adicionales" />
                        <textarea id="comments" name="comments" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('comments') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Guardar Reserva') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>