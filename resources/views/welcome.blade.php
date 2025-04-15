<x-layouts.app2 :title="__('Inicio')" class="bg-white dark:bg-black">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="mb-12 text-center">
            <h1 class="mb-4 text-4xl font-bold text-black dark:text-white">{{ __('Bienvenido a nuestra escuela') }}</h1>
            <p class="mx-auto max-w-2xl text-lg text-gray-600 dark:text-gray-300">{{ __('Descubre nuestras academias y cursos disponibles para tus hijos. Ofrecemos una educación de calidad y desarrollo integral.') }}</p>
        </div>

      

        <!-- Academias Section -->
        <section class="mb-12">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Nuestras Academias') }}</h2>
                <p class="mt-2 text-neutral-600 dark:text-neutral-400">{{ __('Explora nuestra oferta educativa y encuentra el curso perfecto.') }}</p>
            </div>
            <livewire:academias-list />
        </section>

        <!-- Matricula Form Section -->
        @auth
            <section class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-black">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black dark:text-white">{{ __('Matricular Estudiante') }}</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('Inscribe a tus hijos en nuestros cursos disponibles.') }}</p>
                </div>
                <livewire:matricula-form />
            </section>
        @else
            <section class="rounded-xl border border-gray-200 bg-white p-6 text-center dark:border-gray-800 dark:bg-black">
                <h2 class="mb-4 text-2xl font-bold text-black dark:text-white">{{ __('¿Listo para inscribir a tus hijos?') }}</h2>
                <p class="mb-6 text-gray-600 dark:text-gray-300">{{ __('Inicia sesión o regístrate para comenzar el proceso de matrícula.') }}</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:bg-white dark:text-black dark:hover:bg-gray-100 dark:focus:ring-white">{{ __('Iniciar sesión') }}</a>
                    <a href="{{ route('register') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-black hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:border-gray-700 dark:bg-black dark:text-white dark:hover:bg-gray-900 dark:focus:ring-white">{{ __('Registrarse') }}</a>
                </div>
            </section>
        @endauth
    </div>
</x-layouts.app2>