<x-layouts.app>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-black dark:text-white">Pagos de Matrícula</h1>
                <a href="{{ route('pagos.index') }}" class="rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm font-semibold text-black hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:bg-blue-900 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                    Volver a la Lista
                </a>
            </div>
            
            <livewire:pagos-matricula :matricula_id="$matricula->id" />
        </div>
    </div>
</x-layouts.app>
