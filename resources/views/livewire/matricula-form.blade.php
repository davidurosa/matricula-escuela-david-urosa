<div class="overflow-hidden rounded-xl border border-blue-200 bg-white p-6 dark:border-blue-800 dark:bg-black">
    <h2 class="mb-4 text-2xl font-bold text-black dark:text-white">{{ isset($curso_id) ? 'Matricular en Curso ' : 'Nueva Matrícula' }}</h2>
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
    
    @if (session()->has('info'))
        <div class="mb-4 rounded-lg bg-blue-100 p-4 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400">
            {{ session('info') }}
        </div>
    @endif

    <!-- Progress bar -->
    <div class="mb-6">
        <div class="mb-2 flex justify-between">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex flex-col items-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $currentStep >= $i ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $i }}
                    </div>
                    <span class="mt-1 text-xs {{ $currentStep >= $i ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                        @switch($i)
                            @case(1)
                                Curso
                                @break
                            @case(2)
                                Estudiante
                                @break
                            @case(3)
                                Pago
                                @break
                            @case(4)
                                Confirmar
                                @break
                        @endswitch
                    </span>
                </div>
                @if ($i < $totalSteps)
                    <div class="relative top-4 h-0.5 flex-1 {{ $currentStep > $i ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                @endif
            @endfor
        </div>
    </div>

    <form wire:submit.prevent="{{ $currentStep === $totalSteps ? 'save' : 'nextStep' }}" class="space-y-6">
        <!-- Step 1: Course Selection -->
        @if ($currentStep === 1)
            <div class="space-y-4">
                <div>
                    <label for="academia_id" class="block text-sm font-medium text-black dark:text-white">Academia</label>
                    <select id="academia_id" wire:model.live="academia_id" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                        <option value="">Seleccione una academia</option>
                        @foreach($academias as $academia)
                            <option value="{{ $academia->id }}">{{ $academia->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="curso_id" class="block text-sm font-medium text-black dark:text-white">Curso</label>
                    <select id="curso_id" wire:model.live="curso_id" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                        <option value="">Seleccione un curso</option>
                        @foreach($this->cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    </select>
                    @error('curso_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                @if ($this->curso_seleccionado)
                    <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                        <h3 class="text-lg font-medium text-black dark:text-white">{{ $this->curso_seleccionado->nombre }}</h3>
                        <p class="mt-1 text-sm text-blue-600 dark:text-blue-400">{{ $this->curso_seleccionado->descripcion }}</p>
                        <div class="mt-2 flex flex-wrap gap-4 text-sm">
                            <span class="text-blue-600 dark:text-blue-400">Costo: ${{ number_format($this->curso_seleccionado->costo, 2) }}</span>
                            <span class="text-blue-600 dark:text-blue-400">Duración: {{ $this->curso_seleccionado->duracion }} horas</span>
                            <span class="text-blue-600 dark:text-blue-400">Modalidad: {{ ucfirst($this->curso_seleccionado->modalidad) }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 2: Student Information -->
        @if ($currentStep === 2)
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-black dark:text-white">Información del Estudiante</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="estudiante_nombre" class="block text-sm font-medium text-black dark:text-white">Nombre</label>
                            <input type="text" id="estudiante_nombre" wire:model="estudiante_nombre" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                            @error('estudiante_nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="estudiante_apellido" class="block text-sm font-medium text-black dark:text-white">Apellido</label>
                            <input type="text" id="estudiante_apellido" wire:model="estudiante_apellido" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                            @error('estudiante_apellido') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="estudiante_fecha_nacimiento" class="block text-sm font-medium text-black dark:text-white">Fecha de Nacimiento</label>
                            <input type="date" id="estudiante_fecha_nacimiento" wire:model="estudiante_fecha_nacimiento" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                            @error('estudiante_fecha_nacimiento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="estudiante_genero" class="block text-sm font-medium text-black dark:text-white">Género</label>
                            <select id="estudiante_genero" wire:model="estudiante_genero" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                                <option value="">Seleccione un género</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                            @error('estudiante_genero') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="estudiante_observaciones" class="block text-sm font-medium text-black dark:text-white">Observaciones</label>
                        <textarea id="estudiante_observaciones" wire:model="estudiante_observaciones" rows="3" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600"></textarea>
                    </div>
                </div>
                
                <!-- Padre Information -->
                <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-black dark:text-white">Información del Representante</h3>
                    
                    <div class="mt-4">
                        <div class="flex items-center">
                            <input id="usar_padre_existente" wire:model.live="usar_padre_existente" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                            <label for="usar_padre_existente" class="ml-2 block text-sm text-black dark:text-white">Usar representante existente</label>
                        </div>
                    </div>
                    
                    @if ($usar_padre_existente)
                        <div class="mt-4">
                            <label for="padre_id" class="block text-sm font-medium text-black dark:text-white">Seleccionar Representante</label>
                            <select id="padre_id" wire:model="padre_id" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                                <option value="">Seleccione un representante</option>
                                @foreach($this->padres as $padre)
                                    <option value="{{ $padre->id }}">{{ $padre->nombre }} - {{ $padre->email }}</option>
                                @endforeach
                            </select>
                            @error('padre_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="padre_nombre" class="block text-sm font-medium text-black dark:text-white">Nombre Completo</label>
                                <input type="text" id="padre_nombre" wire:model="padre_nombre" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                                @error('padre_nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="padre_email" class="block text-sm font-medium text-black dark:text-white">Correo Electrónico</label>
                                    <input type="email" id="padre_email" wire:model="padre_email" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                                    @error('padre_email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="padre_telefono" class="block text-sm font-medium text-black dark:text-white">Teléfono</label>
                                    <input type="text" id="padre_telefono" wire:model="padre_telefono" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                                    @error('padre_telefono') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Step 3: Payment Method -->
        @if ($currentStep === 3)
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white">Método de Pago</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="metodo_pago_efectivo" wire:model="metodo_pago" type="radio" value="efectivo" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                            <label for="metodo_pago_efectivo" class="ml-3 block text-sm text-black dark:text-white">Efectivo</label>
                        </div>
                        <div class="flex items-center">
                            <input id="metodo_pago_transferencia" wire:model="metodo_pago" type="radio" value="transferencia" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-black dark:text-blue-400">
                            <label for="metodo_pago_transferencia" class="ml-3 block text-sm text-black dark:text-white">Transferencia Bancaria</label>
                        </div>
                    </div>
                    @error('metodo_pago') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                @if ($metodo_pago === 'transferencia')
                    <div>
                        <label for="referencia_pago" class="block text-sm font-medium text-black dark:text-white">Referencia de Pago</label>
                        <input type="text" id="referencia_pago" wire:model="referencia_pago" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                        @error('referencia_pago') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div>
                    <label for="monto_inicial" class="block text-sm font-medium text-black dark:text-white">Monto a pagar ahora</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">$</span>
                        <input type="number" step="0.01" id="monto_inicial" wire:model="monto_inicial" placeholder="{{ $this->curso_seleccionado ? $this->curso_seleccionado->costo : '0.00' }}" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 pl-8 pr-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                    </div>
                    @error('monto_inicial') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                @if ($this->curso_seleccionado)
                    <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                        <h3 class="text-lg font-medium text-black dark:text-white">Resumen de Pago</h3>
                        <p class="mt-2 text-blue-600 dark:text-blue-400">Curso: {{ $this->curso_seleccionado->nombre }}</p>
                        <p class="mt-1 text-2xl font-bold text-black dark:text-white">Total del curso: ${{ number_format($this->curso_seleccionado->costo, 2) }}</p>
                        <p class="mt-1 text-lg font-medium text-blue-600 dark:text-blue-400">Pago inicial: ${{ number_format($monto_inicial ?: $this->curso_seleccionado->costo, 2) }}</p>
                        @if(($monto_inicial ?: $this->curso_seleccionado->costo) < $this->curso_seleccionado->costo)
                            <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">Saldo pendiente: ${{ number_format($this->curso_seleccionado->costo - ($monto_inicial ?: 0), 2) }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 4: Confirmation -->
        @if ($currentStep === 4)
            <div class="space-y-4">
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                    <h3 class="text-lg font-medium text-black dark:text-white">Resumen de Matrícula</h3>
                    
                    @if ($this->curso_seleccionado)
                        <div class="mt-3">
                            <h4 class="font-medium text-black dark:text-white">Curso</h4>
                            <p class="text-blue-600 dark:text-blue-400">{{ $this->curso_seleccionado->nombre }}</p>
                            <p class="text-sm text-blue-600 dark:text-blue-400">Costo: ${{ number_format($this->curso_seleccionado->costo, 2) }}</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        <h4 class="font-medium text-black dark:text-white">Estudiante</h4>
                        <p class="text-blue-600 dark:text-blue-400">{{ $estudiante_nombre }} {{ $estudiante_apellido }}</p>
                    </div>

                    <div class="mt-3">
                        <h4 class="font-medium text-black dark:text-white">Método de Pago</h4>
                        <p class="text-blue-600 dark:text-blue-400">{{ $metodo_pago === 'efectivo' ? 'Efectivo' : 'Transferencia Bancaria' }}</p>
                        @if ($metodo_pago === 'transferencia' && $referencia_pago)
                            <p class="text-sm text-blue-600 dark:text-blue-400">Referencia: {{ $referencia_pago }}</p>
                        @endif
                    </div>

                    <div class="mt-3">
                        <h4 class="font-medium text-black dark:text-white">Fecha de Inicio</h4>
                        <div>
                            <input type="date" id="fecha_inicio" wire:model="fecha_inicio" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                            @error('fecha_inicio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between pt-4">
            @if ($currentStep > 1)
                <button type="button" wire:click="previousStep" class="rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm font-semibold text-black hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:bg-blue-900 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                    Anterior
                </button>
            @else
                <div></div>
            @endif

            <button type="submit" class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-white dark:text-black dark:hover:bg-blue-100 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                {{ $currentStep === $totalSteps ? 'Confirmar Matrícula' : 'Siguiente' }}
            </button>
        </div>
    </form>
</div>
