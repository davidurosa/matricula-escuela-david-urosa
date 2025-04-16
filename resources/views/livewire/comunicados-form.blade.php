<div class="overflow-hidden rounded-xl border border-blue-200 bg-white p-6 dark:border-blue-800 dark:bg-black">
    <h2 class="mb-4 text-2xl font-bold text-black dark:text-white">Envío de Comunicados</h2>
    
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-blue-100 p-4 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-800/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="mb-6">
        <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Seleccionar Destinatarios</h3>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-black dark:text-white">Filtrar por:</label>
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="filtro_todos" wire:model.live="filtro_tipo" type="radio" value="todos" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                    <label for="filtro_todos" class="ml-3 block text-sm text-black dark:text-white">Todos los padres</label>
                </div>
                <div class="flex items-center">
                    <input id="filtro_curso" wire:model.live="filtro_tipo" type="radio" value="curso" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                    <label for="filtro_curso" class="ml-3 block text-sm text-black dark:text-white">Padres por curso</label>
                </div>
                <div class="flex items-center">
                    <input id="filtro_edad" wire:model.live="filtro_tipo" type="radio" value="edad" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                    <label for="filtro_edad" class="ml-3 block text-sm text-black dark:text-white">Padres por edad de estudiantes</label>
                </div>
            </div>
        </div>
        
        @if ($filtro_tipo === 'curso')
            <div class="mb-4">
                <label for="curso_id" class="block text-sm font-medium text-black dark:text-white">Seleccionar Curso</label>
                <select id="curso_id" wire:model="curso_id" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                    <option value="">Seleccione un curso</option>
                    @foreach($this->cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        
        @if ($filtro_tipo === 'edad')
            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="edad_min" class="block text-sm font-medium text-black dark:text-white">Edad Mínima</label>
                    <input type="number" id="edad_min" wire:model="edad_min" min="0" max="100" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                </div>
                <div>
                    <label for="edad_max" class="block text-sm font-medium text-black dark:text-white">Edad Máxima</label>
                    <input type="number" id="edad_max" wire:model="edad_max" min="0" max="100" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                </div>
            </div>
        @endif
        
        <div class="mt-4">
            <button type="button" wire:click="aplicarFiltro" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                Aplicar Filtro
            </button>
        </div>
    </div>
    
    @if ($this->total_padres_seleccionados > 0)
        <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
            <h3 class="text-lg font-medium text-black dark:text-white">Destinatarios Seleccionados ({{ $this->total_padres_seleccionados }})</h3>
            
            <div class="mt-4 max-h-60 overflow-y-auto">
                <ul class="space-y-2">
                    @foreach($this->padres as $padre)
                        <li class="rounded-lg bg-white p-2 dark:bg-gray-800">
                            <p class="font-medium text-black dark:text-white">{{ $padre->nombre }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $padre->email }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                Estudiantes: 
                                @foreach($padre->estudiantes as $estudiante)
                                    {{ $estudiante->nombre }} {{ $estudiante->apellido }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Redactar Comunicado</h3>
            
            <form wire:submit.prevent="enviarComunicado" class="space-y-4">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-black dark:text-white">Título</label>
                    <input type="text" id="titulo" wire:model="titulo" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                    @error('titulo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="mensaje" class="block text-sm font-medium text-black dark:text-white">Mensaje</label>
                    <textarea id="mensaje" wire:model="mensaje" rows="5" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600"></textarea>
                    @error('mensaje') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                        Enviar Comunicado
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-center dark:border-yellow-800 dark:bg-yellow-900/20">
            <p class="text-yellow-700 dark:text-yellow-400">Seleccione los criterios de filtrado y haga clic en "Aplicar Filtro" para seleccionar destinatarios.</p>
        </div>
    @endif
</div>
