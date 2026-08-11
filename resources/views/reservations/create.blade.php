<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Reserva
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de llegada</label>
                        <input type="date" name="fecha_llegada" value="{{ old('fecha_llegada') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de salida</label>
                        <input type="date" name="fecha_salida" value="{{ old('fecha_salida') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número de huéspedes</label>
                        <input type="number" name="numero_huespedes" min="1" max="20"
                               value="{{ old('numero_huespedes') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de habitación</label>
                        <select name="tipo_habitacion" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">-- Selecciona --</option>
                            <option value="sencilla">Sencilla</option>
                            <option value="doble">Doble</option>
                            <option value="suite">Suite</option>
                            <option value="familiar">Familiar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Comentarios adicionales</label>
                        <textarea name="comentarios" rows="3"
                                  class="mt-1 block w-full rounded border-gray-300">{{ old('comentarios') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('reservations.index') }}"
                           class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-black rounded hover:bg-emerald-700">
                            Guardar reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>