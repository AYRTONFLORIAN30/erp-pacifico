<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Modificar Venta: {{ $venta->numero_comprobante }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    <p class="font-bold">Hay errores en el formulario:</p>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('ventas.update', $venta->id) }}" method="POST" x-data="buscadorClientes()">
                @csrf
                @method('PUT')
                
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-700">Documento & Guía</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Emisión</label>
                            <input type="date" name="fecha" required value="{{ old('fecha', $venta->fecha->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo Comprobante</label>
                            <select name="tipo_comprobante" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="FACTURA" {{ old('tipo_comprobante', $venta->tipo_comprobante) == 'FACTURA' ? 'selected' : '' }}>FACTURA</option>
                                <option value="BOLETA" {{ old('tipo_comprobante', $venta->tipo_comprobante) == 'BOLETA' ? 'selected' : '' }}>BOLETA</option>
                                <option value="NOT CRED FACTURA" {{ old('tipo_comprobante', $venta->tipo_comprobante) == 'NOT CRED FACTURA' ? 'selected' : '' }}>NOT CRED FACTURA</option>
                                <option value="NOT CRED BOLETA" {{ old('tipo_comprobante', $venta->tipo_comprobante) == 'NOT CRED BOLETA' ? 'selected' : '' }}>NOT CRED BOLETA</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Serie - Correlativo</label>
                            <input type="text" name="numero_comprobante" required value="{{ old('numero_comprobante', $venta->numero_comprobante) }}" placeholder="Ej: F001-000452" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-mono uppercase">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Código Guía (Opcional)</label>
                            <input type="text" name="codigo_guia" value="{{ old('codigo_guia', $venta->codigo_guia) }}" placeholder="Ej: EG07" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Número Guía (Opcional)</label>
                            <input type="text" name="numero_guia" value="{{ old('numero_guia', $venta->numero_guia) }}" placeholder="Ej: 002290" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-700">Cliente & Ubicación</h3>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">RUC / DNI</label>
                            <input type="text" name="ruc" x-model="ruc" @input="buscar()" maxlength="11" placeholder="Ej: 20123..." class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-mono">
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
                                <option value="Fredy C." {{ old('vendedor', $venta->vendedor) == 'Fredy C.' ? 'selected' : '' }}>Fredy C.</option>
                                <option value="Of. Huasqui" {{ old('vendedor', $venta->vendedor) == 'Of. Huasqui' ? 'selected' : '' }}>Of. Huasqui</option>
                                <option value="Daniel B." {{ old('vendedor', $venta->vendedor) == 'Daniel B.' ? 'selected' : '' }}>Daniel B.</option>
                                <option value="Procesos/Licitacio" {{ old('vendedor', $venta->vendedor) == 'Procesos/Licitacio' ? 'selected' : '' }}>Procesos/Licitacio</option>
                                <option value="Ing. Andi" {{ old('vendedor', $venta->vendedor) == 'Ing. Andi' ? 'selected' : '' }}>Ing. Andi</option>
                                <option value="Sr. Ricardo" {{ old('vendedor', $venta->vendedor) == 'Sr. Ricardo' ? 'selected' : '' }}>Sr. Ricardo</option>
                                <option value="Planta" {{ old('vendedor', $venta->vendedor) == 'Planta' ? 'selected' : '' }}>Planta</option>
                                <option value="Traslado" {{ old('vendedor', $venta->vendedor) == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Ing. Elias" {{ old('vendedor', $venta->vendedor) == 'Ing. Elias' ? 'selected' : '' }}>Ing. Elias</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Zona</label>
                            <select name="zona" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="">Seleccione...</option>
                                <option value="Zona 1.1" {{ old('zona', $venta->zona) == 'Zona 1.1' ? 'selected' : '' }}>Zona 1.1</option>
                                <option value="Zona 1.2" {{ old('zona', $venta->zona) == 'Zona 1.2' ? 'selected' : '' }}>Zona 1.2</option>
                                <option value="Zona 2.1" {{ old('zona', $venta->zona) == 'Zona 2.1' ? 'selected' : '' }}>Zona 2.1</option>
                                <option value="Zona 2.2" {{ old('zona', $venta->zona) == 'Zona 2.2' ? 'selected' : '' }}>Zona 2.2</option>
                                <option value="Zona 2.3" {{ old('zona', $venta->zona) == 'Zona 2.3' ? 'selected' : '' }}>Zona 2.3</option>
                                <option value="Zona 2.4" {{ old('zona', $venta->zona) == 'Zona 2.4' ? 'selected' : '' }}>Zona 2.4</option>
                                <option value="Zona 3.1" {{ old('zona', $venta->zona) == 'Zona 3.1' ? 'selected' : '' }}>Zona 3.1</option>
                                <option value="Zona 3.2" {{ old('zona', $venta->zona) == 'Zona 3.2' ? 'selected' : '' }}>Zona 3.2</option>
                                <option value="Zona 3.3" {{ old('zona', $venta->zona) == 'Zona 3.3' ? 'selected' : '' }}>Zona 3.3</option>
                                <option value="Zona 3.4" {{ old('zona', $venta->zona) == 'Zona 3.4' ? 'selected' : '' }}>Zona 3.4</option>
                                <option value="Zona 3.5" {{ old('zona', $venta->zona) == 'Zona 3.5' ? 'selected' : '' }}>Zona 3.5</option>
                                <option value="Traslado" {{ old('zona', $venta->zona) == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lugar / Distrito</label>
                            <input type="text" name="lugar" x-model="lugar" placeholder="Ej: Satipo" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Detalle (Opcional)</label>
                             <input type="text" name="detalle" value="{{ old('detalle', $venta->detalle) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition uppercase">
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
                        <button type="button" id="btn-add-producto" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar Producto
                        </button>
                    </div>

                    <div class="p-6 relative z-10">
                        <div id="productos-container" class="space-y-4">
                            </div>
                        
                        <div class="mt-6 bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-start justify-between">
                             <div class="flex items-center mt-2">
                                <input type="hidden" name="con_igv" value="0">
                                <input type="checkbox" name="con_igv" id="con_igv" value="1" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-5 w-5" {{ old('con_igv', $venta->con_igv) ? 'checked' : '' }}>
                                <label for="con_igv" class="ml-2 text-sm font-bold text-slate-700">Incluye IGV</label>
                             </div>
                             
                             <div class="text-right flex flex-col items-end">
                                <span class="block text-xs text-emerald-600 uppercase font-bold tracking-wider mb-1">Total a Pagar</span>
                                <div class="relative">
                                    <span class="absolute left-0 top-1 text-emerald-700 font-bold text-xl">S/</span>
                                    <input type="number" step="0.01" id="total" name="total_facturado" value="{{ old('total_facturado', $venta->total_facturado) }}" readonly class="pl-8 w-48 bg-transparent border-0 text-3xl font-black text-emerald-700 focus:ring-0 text-right p-0" placeholder="0.00">
                                </div>
                             </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-yellow-400">
                    <div class="px-6 py-4 border-b border-yellow-200 bg-yellow-50 flex items-center gap-3">
                        <input type="hidden" name="aplica_detraccion" value="0">
                        <input type="checkbox" id="aplica_detraccion" x-model="aplica_detraccion" name="aplica_detraccion" value="1" class="rounded border-yellow-500 text-yellow-600 shadow-sm focus:ring-yellow-500 h-5 w-5 cursor-pointer">
                        <label for="aplica_detraccion" class="text-sm font-extrabold text-yellow-800 uppercase tracking-wide cursor-pointer">
                            APLICAR DETRACCIÓN (SPOT)
                        </label>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6" x-show="aplica_detraccion" x-transition>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Servicio (%)</label>
                            <select name="tipo_detraccion" id="tipo_detraccion" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition">
                                <option value="">Seleccione...</option>
                                <option value="1.5" {{ old('tipo_detraccion', $venta->tipo_detraccion) == '1.5' ? 'selected' : '' }}>1.5% - Insumos Agrícolas / Fertilizantes</option>
                                <option value="10.0" {{ old('tipo_detraccion', $venta->tipo_detraccion) == '10.0' ? 'selected' : '' }}>10.00% - Demás Bienes Grabados con IGV</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Detracción (S/)</label>
                            <input type="number" step="0.01" name="monto_detraccion" id="monto_detraccion" value="{{ old('monto_detraccion', $venta->monto_detraccion) }}" readonly class="w-full rounded-lg border-slate-200 bg-slate-100 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition text-red-600 font-bold cursor-not-allowed" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Pago Detr.</label>
                            <input type="date" name="fecha_pago_detraccion" value="{{ old('fecha_pago_detraccion', $venta->fecha_pago_detraccion) }}" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-slate-100">
                     <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Forma de Pago</label>
                             <select name="forma_pago" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                <option value="Contado" {{ old('forma_pago', $venta->forma_pago) == 'Contado' ? 'selected' : '' }}>Contado</option>
                                <option value="Credito" {{ old('forma_pago', $venta->forma_pago) == 'Credito' ? 'selected' : '' }}>Crédito</option>
                                <option value="Mixto" {{ old('forma_pago', $venta->forma_pago) == 'Mixto' ? 'selected' : '' }}>Mixto</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Contado</label>
                            <input type="number" step="0.01" name="monto_contado" value="{{ old('monto_contado', $venta->monto_contado) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" placeholder="0.00">
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Crédito</label>
                            <input type="number" step="0.01" name="monto_credito" value="{{ old('monto_credito', $venta->monto_credito) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Destino Dinero</label>
                             <select name="movimiento" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition font-bold text-slate-700">
                                <option value="CAJA" {{ old('movimiento', $venta->movimiento) == 'CAJA' ? 'selected' : '' }}>💵 CAJA EFECTIVO</option>
                                <option value="DEPOSITO" {{ old('movimiento', $venta->movimiento) == 'DEPOSITO' ? 'selected' : '' }}>🏦 BANCO / DEPÓSITO</option>
                            </select>
                        </div>
                     </div>
                </div>

                <div class="flex items-center justify-end gap-4 mb-12">
                    <a href="{{ route('ventas.index') }}" class="px-6 py-3 bg-white border border-slate-300 rounded-xl font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('productos-container');
            const btnAdd = document.getElementById('btn-add-producto');
            const totalInput = document.getElementById('total');
            
            // Variables para la detracción
            const selectDetraccion = document.getElementById('tipo_detraccion');
            const inputMontoDetraccion = document.getElementById('monto_detraccion');
            const checkboxDetraccion = document.getElementById('aplica_detraccion');

            // Datos de Laravel para pre-cargar la vista
            const productosData = @json($productos);
            const detallesVenta = @json($venta->detalles);

            function calcularDetraccion() {
                if (checkboxDetraccion.checked) {
                    const total = parseFloat(totalInput.value) || 0;
                    const porcentaje = parseFloat(selectDetraccion.value) || 0;
                    if (total > 0 && porcentaje > 0) {
                        inputMontoDetraccion.value = (total * (porcentaje / 100)).toFixed(2);
                    } else {
                        inputMontoDetraccion.value = '';
                    }
                } else {
                    inputMontoDetraccion.value = '';
                    selectDetraccion.value = ''; 
                }
            }

            function calcularTotalGeneral() {
                let total = 0;
                const rows = document.querySelectorAll('.producto-row');
                rows.forEach(row => {
                    const cant = parseFloat(row.querySelector('.calc-cantidad').value) || 0;
                    const precio = parseFloat(row.querySelector('.calc-precio').value) || 0;
                    total += (cant * precio);
                });
                totalInput.value = total.toFixed(2);
                calcularDetraccion();
            }

            selectDetraccion.addEventListener('change', calcularDetraccion);
            checkboxDetraccion.addEventListener('change', calcularDetraccion);

            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('calc-cantidad') || e.target.classList.contains('calc-precio')) {
                    calcularTotalGeneral();
                }
            });

            container.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-remove-producto');
                if (btn) {
                    btn.closest('.producto-row').remove();
                    calcularTotalGeneral();
                    const rows = document.querySelectorAll('.producto-row');
                    if (rows.length === 1) {
                        rows[0].querySelector('.btn-remove-producto').classList.add('hidden');
                    }
                }
            });

            // Función para renderizar una fila de producto (vacía o llena)
            function renderRow(detalle = null) {
                const row = document.createElement('div');
                row.className = "producto-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-slate-100";
                
                let optionsHtml = '<option value="">Seleccione un producto...</option>';
                productosData.forEach(p => {
                    let isSelected = (detalle && detalle.producto_id == p.id) ? 'selected' : '';
                    optionsHtml += `<option value="${p.id}" ${isSelected}>${p.nombre} (Stock: ${p.stock})</option>`;
                });

                row.innerHTML = `
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Producto</label>
                        <select name="producto_id[]" required class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition font-medium text-sm">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cantidad</label>
                        <input type="number" step="0.01" name="cantidad[]" value="${detalle ? detalle.cantidad : ''}" required class="calc-cantidad w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-center font-bold text-sm" placeholder="0.00">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tonelada (Ton.)</label>
                        <input type="number" step="0.01" name="tonelada[]" value="${detalle ? (detalle.tonelada || '') : ''}" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-center text-sm" placeholder="0.00">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">P. Unitario (S/)</label>
                        <input type="number" step="0.01" name="precio_unitario[]" value="${detalle ? detalle.precio_unitario : ''}" required class="calc-precio w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-right font-bold text-sm" placeholder="0.00">
                    </div>
                    <div class="md:col-span-1 flex justify-center pb-1">
                        <button type="button" class="btn-remove-producto text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Eliminar fila">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                `;
                
                container.appendChild(row);
                
                const rows = document.querySelectorAll('.producto-row');
                if (rows.length === 1) {
                    rows[0].querySelector('.btn-remove-producto').classList.add('hidden');
                } else {
                    rows.forEach(r => r.querySelector('.btn-remove-producto').classList.remove('hidden'));
                }
            }

            // Inicializar filas
            if (detallesVenta.length > 0) {
                detallesVenta.forEach(detalle => renderRow(detalle));
            } else {
                renderRow(); // Si no hay detalles, dibuja una vacía
            }

            btnAdd.addEventListener('click', () => renderRow());
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('buscadorClientes', () => ({
                ruc: '{{ old('ruc', $venta->cliente_ruc) }}',
                cliente: '{{ old('cliente', $venta->cliente) }}',
                lugar: '{{ old('lugar', $venta->lugar) }}',
                buscando: false,
                mensajeError: false,
                mensajeExito: false,
                aplica_detraccion: {{ old('aplica_detraccion', $venta->aplica_detraccion) ? 'true' : 'false' }},

                async buscar() {
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
                            console.error("Error al buscar:", error);
                        } finally {
                            this.buscando = false;
                        }
                    } else {
                        this.mensajeError = false;
                        this.mensajeExito = false;
                    }
                }
            }));
        });
    </script>
</x-app-layout>