<div class="overflow-hidden rounded-xl border border-blue-200 bg-white p-6 dark:border-blue-800 dark:bg-black">
    <h2 class="mb-4 text-2xl font-bold text-black dark:text-white">Gestión de Pagos</h2>
    
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
    
    @if ($this->matricula)
        <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
            <h3 class="text-lg font-medium text-black dark:text-white">Información de Matrícula</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estudiante:</p>
                    <p class="text-blue-600 dark:text-blue-400">{{ $this->matricula->estudiante->nombre }} {{ $this->matricula->estudiante->apellido }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Curso:</p>
                    <p class="text-blue-600 dark:text-blue-400">{{ $this->matricula->curso->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Costo Total:</p>
                    <p class="text-blue-600 dark:text-blue-400">${{ number_format($this->costo_curso, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de Inscripción:</p>
                    <p class="text-blue-600 dark:text-blue-400">{{ $this->matricula->fecha_inscripcion->format('d/m/Y') }}</p>
                </div>
            </div>
            
            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-blue-200 bg-white p-3 dark:border-blue-800 dark:bg-black">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pagado:</p>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($this->total_pagado, 2) }}</p>
                </div>
                <div class="rounded-lg border border-blue-200 bg-white p-3 dark:border-blue-800 dark:bg-black">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Saldo Pendiente:</p>
                    <p class="text-xl font-bold {{ $this->saldo_pendiente > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        ${{ number_format($this->saldo_pendiente, 2) }}
                    </p>
                </div>
                <div class="rounded-lg border border-blue-200 bg-white p-3 dark:border-blue-800 dark:bg-black">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estado:</p>
                    <p class="text-xl font-bold {{ $this->saldo_pendiente > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $this->saldo_pendiente > 0 ? 'Pendiente' : 'Pagado' }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Registrar Nuevo Pago</h3>
            <form wire:submit.prevent="registrarPago" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="monto" class="block text-sm font-medium text-black dark:text-white">Monto</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">$</span>
                            <input type="number" step="0.01" id="monto" wire:model="monto" placeholder="0.00" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 pl-8 pr-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                        </div>
                        @error('monto') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-black dark:text-white">Fecha de Pago</label>
                        <input type="date" id="fecha" wire:model="fecha" class="mt-1 block w-full rounded-md border-2 border-blue-300 bg-white py-2.5 px-3 text-black shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-700 dark:bg-black dark:text-white dark:hover:border-blue-600">
                        @error('fecha') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                
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
                
                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:ring-offset-black">
                        Registrar Pago
                    </button>
                </div>
            </form>
        </div>
        
        <div>
            <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Historial de Pagos</h3>
            @if ($this->pagos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-200 dark:divide-blue-800">
                        <thead class="bg-blue-50 dark:bg-blue-900/20">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Método</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-200 bg-white dark:divide-blue-800 dark:bg-black">
                            @foreach ($this->pagos as $pago)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $pago->fecha->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($pago->metodo) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($pago->monto, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-center dark:border-blue-800 dark:bg-blue-900/20">
                    <p class="text-gray-500 dark:text-gray-400">No hay pagos registrados para esta matrícula.</p>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-center dark:border-blue-800 dark:bg-blue-900/20">
            <p class="text-gray-500 dark:text-gray-400">Seleccione una matrícula para gestionar sus pagos.</p>
        </div>
    @endif
</div>
