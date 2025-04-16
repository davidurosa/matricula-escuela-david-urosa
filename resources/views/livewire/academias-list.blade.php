<div class="space-y-10">
    @foreach($academias as $academia)
        <div class="overflow-hidden rounded-xl border border-blue-300 bg-gradient-to-br from-white to-blue-50 w-full shadow-md dark:from-gray-900 dark:to-black dark:border-blue-700">
            <div class="p-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-blue-300">{{ $academia->nombre }}</h3>
                    <span class="inline-flex items-center rounded-full bg-blue-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm dark:bg-blue-500">
                        {{ $academia->cursos_count }} {{ __('cursos') }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-8">{{ $academia->descripcion }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($academia->cursos as $curso)
                        <div class="rounded-lg border border-blue-200 bg-white p-5 dark:bg-gray-800 dark:border-blue-900 h-full flex flex-col shadow-sm hover:shadow-md transition-shadow duration-300 hover:border-blue-400 dark:hover:border-blue-600">
                            <p class="font-semibold text-lg text-gray-800 dark:text-white mb-3">{{ $curso->nombre }}</p>
                            <div class="space-y-2 text-sm mb-4">
                                <p class="text-blue-700 dark:text-blue-400">{{ __('Duración') }}: <span class="font-medium">{{ $curso->duracion }} horas</span></p>
                                <p class="text-blue-700 dark:text-blue-400">{{ __('Costo') }}: <span class="font-medium">${{ number_format($curso->costo, 2) }}</span></p>
                                <p class="text-blue-700 dark:text-blue-400">{{ __('Modalidad') }}: <span class="font-medium">{{ ucfirst($curso->modalidad) }}</span></p>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-auto">{{ $curso->descripcion }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
