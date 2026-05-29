<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Nueva Venta
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen relative">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any() || session('error'))
                <div x-data="{ openAlert: true }" x-show="openAlert" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity" @click="openAlert = false"></div>
                    <div class="relative bg-white rounded-2xl text-center shadow-2xl transform transition-all max-w-md w-full p-8 z-10 flex flex-col items-center border border-slate-100">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-[6px] border-red-100 bg-white mb-5 shadow-sm">
                            <svg class="h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black leading-6 text-slate-800 mb-5 tracking-tight">¡Atención!</h3>
                        <div class="text-sm text-slate-600 font-medium mb-8 w-full">
                            @if (session('error'))
                                <p class="text-sm text-red-700 font-bold bg-red-50 p-4 rounded-xl border border-red-100">{{ session('error') }}</p>
                            @endif
                            @if ($errors->any())
                                <p class="font-bold text-red-600 mb-3 mt-4 text-left px-2">Por favor, verifica lo siguiente:</p>
                                <ul class="list-disc list-inside text-left bg-red-50 p-4 rounded-xl border border-red-100 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-700 font-medium">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <button type="button" @click="openAlert = false" class="inline-flex justify-center rounded-xl border border-transparent bg-[#0ea5e9] px-8 py-3.5 text-base font-black tracking-wide text-white shadow-md hover:bg-[#0284c7] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] focus:ring-offset-2 transition-all w-full">
                            Entendido, cerrar
                        </button>
                    </div>
                </div>
            @endif

            <form action="{{ route('ventas.store') }}" method="POST" x-data="formularioVentas()">
                @csrf
                
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-700">Documento & Guía</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Emisión</label>
                            <input type="date" name="fecha" required value="{{ old('fecha', date('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo Comprobante</label>
                            <select name="tipo_comprobante" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="FACTURA" {{ old('tipo_comprobante') == 'FACTURA' ? 'selected' : '' }}>FACTURA</option>
                                <option value="BOLETA" {{ old('tipo_comprobante') == 'BOLETA' ? 'selected' : '' }}>BOLETA</option>
                                <option value="NOT CRED FACTURA" {{ old('tipo_comprobante') == 'NOT CRED FACTURA' ? 'selected' : '' }}>NOT CRED FACTURA</option>
                                <option value="NOT CRED BOLETA" {{ old('tipo_comprobante') == 'NOT CRED BOLETA' ? 'selected' : '' }}>NOT CRED BOLETA</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Serie - Correlativo</label>
                            <input type="text" name="numero_comprobante" required value="{{ old('numero_comprobante') }}" placeholder="Ej: F001-000452" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-mono uppercase">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Código Guía (Opcional)</label>
                            <input type="text" name="codigo_guia" value="{{ old('codigo_guia') }}" placeholder="Ej: EG07" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Número Guía</label>
                            <input type="text" name="numero_guia" value="{{ old('numero_guia') }}" placeholder="Ej: 002290" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-slate-700">Cliente & Ubicación</h3>
                        </div>
                        
                        <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 px-3 py-1.5 rounded-lg shadow-inner w-full md:w-auto">
                            <label class="text-xs font-black text-indigo-700 uppercase tracking-widest whitespace-nowrap">📦 Despachar Desde:</label>
                            <select name="almacen_origen" x-model="almacenActual" class="text-sm font-bold border-0 bg-transparent text-indigo-900 focus:ring-0 cursor-pointer p-0 pr-6 w-full md:w-auto">
                                <option value="Tarma">Tarma</option>
                                <option value="Lima">Lima</option>
                                <option value="Lambayeque">Lambayeque</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">RUC / DNI</label>
                            <input type="text" name="ruc" x-model="ruc" @input="buscarCliente()" maxlength="11" placeholder="Ej: 20123..." class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-mono">
                            
                            <span x-show="buscando" class="text-[10px] text-blue-500 font-bold mt-1 block tracking-tight">Buscando...</span>
                            <span x-show="mensajeError" class="text-[10px] text-red-500 font-bold mt-1 block tracking-tight" style="display: none;">Nuevo. Llenar manual.</span>
                            <span x-show="mensajeExito" class="text-[10px] text-emerald-500 font-bold mt-1 block tracking-tight" style="display: none;">¡Encontrado!</span>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cliente / Razón Social</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <input type="text" name="cliente" x-model="cliente" required class="pl-10 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase" placeholder="Buscar RUC o escribir cliente...">
                            </div>
                        </div>

                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Vendedor</label>
                             <select name="vendedor" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="">Seleccione...</option>
                                <option value="Fredy C." {{ old('vendedor') == 'Fredy C.' ? 'selected' : '' }}>Fredy C.</option>
                                <option value="Of. Huasqui" {{ old('vendedor') == 'Of. Huasqui' ? 'selected' : '' }}>Of. Huasqui</option>
                                <option value="Daniel B." {{ old('vendedor') == 'Daniel B.' ? 'selected' : '' }}>Daniel B.</option>
                                <option value="Procesos/Licitacio" {{ old('vendedor') == 'Procesos/Licitacio' ? 'selected' : '' }}>Procesos/Licitacio</option>
                                <option value="Ing. Andi" {{ old('vendedor') == 'Ing. Andi' ? 'selected' : '' }}>Ing. Andi</option>
                                <option value="Sr. Ricardo" {{ old('vendedor') == 'Sr. Ricardo' ? 'selected' : '' }}>Sr. Ricardo</option>
                                <option value="Planta" {{ old('vendedor') == 'Planta' ? 'selected' : '' }}>Planta</option>
                                <option value="Traslado" {{ old('vendedor') == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Ing. Elias" {{ old('vendedor') == 'Ing. Elias' ? 'selected' : '' }}>Ing. Elias</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Zona</label>
                            <select name="zona" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="">Seleccione...</option>
                                <option value="Zona 1.1" {{ old('zona') == 'Zona 1.1' ? 'selected' : '' }}>Zona 1.1</option>
                                <option value="Zona 1.2" {{ old('zona') == 'Zona 1.2' ? 'selected' : '' }}>Zona 1.2</option>
                                <option value="Zona 2.1" {{ old('zona') == 'Zona 2.1' ? 'selected' : '' }}>Zona 2.1</option>
                                <option value="Zona 2.2" {{ old('zona') == 'Zona 2.2' ? 'selected' : '' }}>Zona 2.2</option>
                                <option value="Zona 2.3" {{ old('zona') == 'Zona 2.3' ? 'selected' : '' }}>Zona 2.3</option>
                                <option value="Zona 2.4" {{ old('zona') == 'Zona 2.4' ? 'selected' : '' }}>Zona 2.4</option>
                                <option value="Zona 3.1" {{ old('zona') == 'Zona 3.1' ? 'selected' : '' }}>Zona 3.1</option>
                                <option value="Zona 3.2" {{ old('zona') == 'Zona 3.2' ? 'selected' : '' }}>Zona 3.2</option>
                                <option value="Zona 3.3" {{ old('zona') == 'Zona 3.3' ? 'selected' : '' }}>Zona 3.3</option>
                                <option value="Zona 3.4" {{ old('zona') == 'Zona 3.4' ? 'selected' : '' }}>Zona 3.4</option>
                                <option value="Zona 3.5" {{ old('zona') == 'Zona 3.5' ? 'selected' : '' }}>Zona 3.5</option>
                                <option value="Traslado" {{ old('zona') == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lugar / Distrito</label>
                            <input type="text" name="lugar" x-model="lugar" placeholder="Ej: Satipo" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Detalle (Opcional)</label>
                             <input type="text" name="detalle" value="{{ old('detalle') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-emerald-200 relative">
                    <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                        <svg class="w-32 h-32 text-emerald-900" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                    </div>
                    
                    <div class="px-6 py-4 border-b border-emerald-100 bg-emerald-50 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-1 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-lg font-bold text-emerald-800">Detalle de la Venta</h3>
                        </div>
                        <button @click="agregarFila()" type="button" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar Producto
                        </button>
                    </div>

                    <div class="p-6 relative z-10">
                        <div class="space-y-4">
                            
                            <template x-for="(fila, index) in filas" :key="fila.id">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="md:col-span-5">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Producto</label>
                                        <select name="producto_id[]" required x-model="fila.producto_id" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition font-medium text-sm">
                                            <option value="">Seleccione un producto...</option>
                                            <template x-for="prod in productosFiltrados" :key="prod.id">
                                                <option :value="prod.id" x-text="`${prod.nombre} (Stock: ${prod.stockActual})`"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cantidad</label>
                                        <input type="number" step="0.01" name="cantidad[]" required x-model="fila.cantidad" @input="calcularTotalGeneral()" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-center font-bold text-sm" placeholder="0.00">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tonelada (Ton.)</label>
                                        <input type="number" step="0.01" name="tonelada[]" x-model="fila.tonelada" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-center text-sm" placeholder="0.00">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">P. Unitario (S/)</label>
                                        <input type="number" step="0.01" name="precio_unitario[]" required x-model="fila.precio" @input="calcularTotalGeneral()" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-right font-bold text-sm" placeholder="0.00">
                                    </div>

                                    <div class="md:col-span-1 flex justify-center pb-1">
                                        <button x-show="filas.length > 1" @click="eliminarFila(index)" type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Eliminar fila">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                        </div>
                        
                        <div class="mt-6 bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-start justify-between">
                             <div class="flex items-center mt-2">
                                <input type="hidden" name="con_igv" value="0">
                                <input type="checkbox" name="con_igv" id="con_igv" value="1" x-model="con_igv" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-5 w-5">
                                <label for="con_igv" class="ml-2 text-sm font-bold text-slate-700">Incluye IGV</label>
                             </div>
                             
                             <div class="text-right flex flex-col items-end">
                                <span class="block text-xs text-emerald-600 uppercase font-bold tracking-wider mb-1">Total a Pagar</span>
                                <div class="relative">
                                    <span class="absolute left-0 top-1 text-emerald-700 font-bold text-xl">S/</span>
                                    <input type="number" step="0.01" name="total_facturado" x-model="total_facturado" readonly class="pl-8 w-48 bg-transparent border-0 text-3xl font-black text-emerald-700 focus:ring-0 text-right p-0" placeholder="0.00">
                                </div>
                             </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-yellow-400">
                    <div class="px-6 py-4 border-b border-yellow-200 bg-yellow-50 flex items-center gap-3">
                        <input type="hidden" name="aplica_detraccion" value="0">
                        <input type="checkbox" id="aplica_detraccion" x-model="aplica_detraccion" @change="calcularDetraccion()" name="aplica_detraccion" value="1" class="rounded border-yellow-500 text-yellow-600 shadow-sm focus:ring-yellow-500 h-5 w-5 cursor-pointer">
                        <label for="aplica_detraccion" class="text-sm font-extrabold text-yellow-800 uppercase tracking-wide cursor-pointer">
                            APLICAR DETRACCIÓN (SPOT)
                        </label>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6" x-show="aplica_detraccion" x-transition>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Servicio (%)</label>
                            <select name="tipo_detraccion" x-model="tipo_detraccion" @change="calcularDetraccion()" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition">
                                <option value="">Seleccione...</option>
                                <option value="1.5">1.5% - Insumos Agrícolas / Fertilizantes</option>
                                <option value="10.0">10.00% - Demás Bienes Grabados con IGV</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Detracción (S/)</label>
                            <input type="number" step="0.01" name="monto_detraccion" x-model="monto_detraccion" readonly class="w-full rounded-lg border-slate-200 bg-slate-100 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition text-red-600 font-bold cursor-not-allowed" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Pago Detr.</label>
                            <input type="date" name="fecha_pago_detraccion" value="{{ old('fecha_pago_detraccion') }}" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                     <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Forma de Pago</label>
                             <select name="forma_pago" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="Contado" {{ old('forma_pago') == 'Contado' ? 'selected' : '' }}>Contado</option>
                                <option value="Credito" {{ old('forma_pago') == 'Credito' ? 'selected' : '' }}>Crédito</option>
                                <option value="Mixto" {{ old('forma_pago') == 'Mixto' ? 'selected' : '' }}>Mixto</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Contado</label>
                            <input type="number" step="0.01" name="monto_contado" value="{{ old('monto_contado') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" placeholder="0.00">
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Crédito</label>
                            <input type="number" step="0.01" name="monto_credito" value="{{ old('monto_credito') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Destino Dinero</label>
                             <select name="movimiento" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-bold text-slate-700">
                                <option value="CAJA" {{ old('movimiento') == 'CAJA' ? 'selected' : '' }}>💵 CAJA EFECTIVO</option>
                                <option value="DEPOSITO" {{ old('movimiento') == 'DEPOSITO' ? 'selected' : '' }}>🏦 BANCO / DEPÓSITO</option>
                            </select>
                        </div>
                     </div>
                </div>

                <div class="flex items-center justify-end gap-4 mb-12">
                    <a href="{{ route('ventas.index') }}" class="px-6 py-3 bg-white border border-slate-300 rounded-xl font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 transform hover:-translate-y-1 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Venta
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formularioVentas', () => ({
                
                // 1. Buscador de Clientes
                ruc: '{{ old('ruc') }}',
                cliente: '{{ old('cliente') }}',
                lugar: '{{ old('lugar') }}',
                buscando: false,
                mensajeError: false,
                mensajeExito: false,

                async buscarCliente() {
                    if (this.ruc.length >= 8) {
                        this.buscando = true;
                        this.mensajeError = false;
                        this.mensajeExito = false;

                        try {
                            let respuesta = await fetch(`/api/buscar-cliente/${this.ruc}`);
                            let datos = await respuesta.json();

                            if (datos.encontrado) {
                                this.cliente = datos.razon_social;
                                this.lugar = datos.lugar || '';
                                this.mensajeExito = true;
                            } else if(this.ruc.length === 11) {
                                this.cliente = '';
                                this.lugar = '';
                                this.mensajeError = true;
                            }
                        } catch (error) {
                            console.error("Error:", error);
                        } finally {
                            this.buscando = false;
                        }
                    } else {
                        this.mensajeError = false;
                        this.mensajeExito = false;
                    }
                },

                // 2. Almacenes y Filtro Dinámico de Productos
                almacenActual: '{{ old('almacen_origen', 'Tarma') }}',
                productosRaw: @json($productos),
                
                get productosFiltrados() {
                    // Magia: Mapea cada producto y extrae solo la cantidad del almacén elegido
                    return this.productosRaw.map(prod => {
                        let stockReal = 0;
                        if(this.almacenActual === 'Tarma') stockReal = prod.stock_tarma;
                        if(this.almacenActual === 'Lima') stockReal = prod.stock_lima;
                        if(this.almacenActual === 'Lambayeque') stockReal = prod.stock_lambayeque;
                        
                        return {
                            id: prod.id,
                            nombre: prod.nombre,
                            stockActual: parseFloat(stockReal).toFixed(2)
                        };
                    });
                },

                // 3. Filas Dinámicas y Cálculos
                filas: [
                    { id: Date.now(), producto_id: '', cantidad: '', tonelada: '', precio: '' }
                ],
                total_facturado: '{{ old('total_facturado', '0.00') }}',
                con_igv: {{ old('con_igv') ? 'true' : 'false' }},
                aplica_detraccion: {{ old('aplica_detraccion') ? 'true' : 'false' }},
                tipo_detraccion: '{{ old('tipo_detraccion', '') }}',
                monto_detraccion: '{{ old('monto_detraccion', '') }}',

                agregarFila() {
                    this.filas.push({ id: Date.now(), producto_id: '', cantidad: '', tonelada: '', precio: '' });
                },

                eliminarFila(index) {
                    this.filas.splice(index, 1);
                    this.calcularTotalGeneral();
                },

                calcularTotalGeneral() {
                    let sum = 0;
                    this.filas.forEach(fila => {
                        let cant = parseFloat(fila.cantidad) || 0;
                        let prec = parseFloat(fila.precio) || 0;
                        sum += (cant * prec);
                    });
                    this.total_facturado = sum.toFixed(2);
                    this.calcularDetraccion();
                },

                calcularDetraccion() {
                    if (this.aplica_detraccion && this.tipo_detraccion) {
                        let total = parseFloat(this.total_facturado) || 0;
                        let porcentaje = parseFloat(this.tipo_detraccion) || 0;
                        if(total > 0 && porcentaje > 0) {
                            this.monto_detraccion = (total * (porcentaje / 100)).toFixed(2);
                        } else {
                            this.monto_detraccion = '';
                        }
                    } else {
                        this.monto_detraccion = '';
                    }
                }
            }));
        });
    </script>
</x-app-layout>



