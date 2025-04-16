<x-layouts.app>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-black dark:text-white">Gestión de Pagos</h1>
                <a href="{{ route('matriculas.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                    Nueva Matrícula
                </a>
            </div>
            
            @if ($matriculas->count() > 0)
                <div class="overflow-hidden rounded-xl border border-blue-200 bg-white dark:border-blue-800 dark:bg-black">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-blue-200 dark:divide-blue-800">
                            <thead class="bg-blue-50 dark:bg-blue-900/20">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Estudiante</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Curso</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha Inscripción</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pagado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Costo Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 bg-white dark:divide-blue-800 dark:bg-black">
                                @foreach ($matriculas as $matricula)
                                    @php
                                        $total_pagado = $matricula->pagos->sum('monto');
                                        $costo_total = $matricula->curso->costo;
                                        $saldo_pendiente = $costo_total - $total_pagado;
                                    @endphp
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $matricula->estudiante->nombre }} {{ $matricula->estudiante->apellido }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $matricula->curso->nombre }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $matricula->fecha_inscripcion->format('d/m/Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-blue-600 dark:text-blue-400">
                                            ${{ number_format($total_pagado, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            ${{ number_format($costo_total, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @if ($saldo_pendiente <= 0)
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Pagado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            <a href="{{ route('pagos.show', $matricula->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Ver Pagos
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-blue-200 bg-white p-6 text-center dark:border-blue-800 dark:bg-black">
                    <p class="text-gray-500 dark:text-gray-400">No hay matrículas registradas en el sistema.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
