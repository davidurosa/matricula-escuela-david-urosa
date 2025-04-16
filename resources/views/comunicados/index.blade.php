<x-layouts.app>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-black dark:text-white">Comunicados</h1>
                <a href="{{ route('comunicados.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                    Nuevo Comunicado
                </a>
            </div>
            
            @if ($comunicados->count() > 0)
                <div class="overflow-hidden rounded-xl border border-blue-200 bg-white dark:border-blue-800 dark:bg-black">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-blue-200 dark:divide-blue-800">
                            <thead class="bg-blue-50 dark:bg-blue-900/20">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Destinatario</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Curso</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Mensaje</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-200 bg-white dark:divide-blue-800 dark:bg-black">
                                @foreach ($comunicados as $comunicado)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comunicado->fecha_envio->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comunicado->titulo }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comunicado->padre ? $comunicado->padre->nombre : 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comunicado->curso ? $comunicado->curso->nombre : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="max-w-xs truncate">{{ $comunicado->mensaje }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-blue-200 bg-white p-6 text-center dark:border-blue-800 dark:bg-black">
                    <p class="text-gray-500 dark:text-gray-400">No hay comunicados registrados en el sistema.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
