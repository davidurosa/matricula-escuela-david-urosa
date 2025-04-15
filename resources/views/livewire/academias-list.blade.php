<div class="grid gap-6 lg:grid-cols-2">
    @foreach($academias as $academia)
        <div class="overflow-hidden rounded-xl border border-green-200 bg-white dark:border-green-800 dark:bg-black">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black dark:text-white">{{ $academia->nombre }}</h3>
                    <span class="inline-flex items-center rounded-lg bg-green-100 px-2 py-1 text-xs font-medium text-black dark:bg-green-800 dark:text-white">
                        {{ $academia->cursos_count }} {{ __('cursos') }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-green-600 dark:text-green-300">{{ $academia->descripcion }}</p>

                <div class="mt-6 space-y-4">
                    @foreach($academia->cursos as $curso)
                        <div class="flex items-center justify-between rounded-lg border border-green-200 p-4 dark:border-green-800">
                            <div class="space-y-1">
                                <p class="font-medium text-black dark:text-white">{{ $curso->nombre }}</p>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-green-600 dark:text-green-300">
                                    <span>{{ __('Duración') }}: {{ $curso->duracion }} horas</span>
                                    <span>•</span>
                                    <span>{{ __('Costo') }}: ${{ number_format($curso->costo, 2) }}</span>
                                    <span>•</span>
                                    <span>{{ __('Modalidad') }}: {{ ucfirst($curso->modalidad) }}</span>
                                </div>
                                <p class="text-sm text-green-600 dark:text-green-300">{{ $curso->descripcion }}</p>
                            </div>
                            @auth
                                <button
                                    wire:click="inscribir({{ $curso->id }})"
                                    class="rounded-lg bg-black px-3 py-2 text-sm font-semibold cursor-pointer text-white hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:bg-white dark:text-black dark:hover:bg-green-100 dark:focus:ring-white"
                                >
                                    {{ __('Inscribir') }}
                                </button>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:hover:bg-primary-500 dark:focus:ring-offset-neutral-800"
                                >
                                    {{ __('Iniciar sesión') }}
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
