<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar Venta Externa
            </h2>
            <a href="{{ route('ventas.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                ← Volver a Ventas
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen" x-data="ventaExternaForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any() || session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">No se pudo actualizar la venta externa:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                @if(session('error'))
                                    <p>{{ session('error') }}</p>
                                @endif
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('ventas_fuera.update', $ventaFuera->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-200">
                    
                    <div class="px-8 py-6 border-b border-slate-100 bg-indigo-50/50 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900">Modificando Datos del Comprobante</h3>
                            <p class="text-sm text-indigo-600">Actualice la información de la venta externa</p>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha</label>
                            <input type="date" name="fecha" required value="{{ old('fecha', $ventaFuera->fecha ? \Carbon\Carbon::parse($ventaFuera->fecha)->format('Y-m-d') : date('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo Comprobante</label>
                            <select name="tipo_comprobante" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                                <option value="FACTURA" {{ old('tipo_comprobante', $ventaFuera->tipo_comprobante) == 'FACTURA' ? 'selected' : '' }}>FACTURA</option>
                                <option value="BOLETA" {{ old('tipo_comprobante', $ventaFuera->tipo_comprobante) == 'BOLETA' ? 'selected' : '' }}>BOLETA</option>
                                <option value="NOT CRED FACTURA" {{ old('tipo_comprobante', $ventaFuera->tipo_comprobante) == 'NOT CRED FACTURA' ? 'selected' : '' }}>NOT CRED FACTURA</option>
                                <option value="NOT CRED BOLETA" {{ old('tipo_comprobante', $ventaFuera->tipo_comprobante) == 'NOT CRED BOLETA' ? 'selected' : '' }}>NOT CRED BOLETA</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Comprobante</label>
                            <input type="text" name="numero_comprobante" value="{{ old('numero_comprobante', $ventaFuera->numero_comprobante) }}" required placeholder="Ej: F001-1234" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm uppercase">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cód. Guía</label>
                            <input type="text" name="codigo_guia" value="{{ old('codigo_guia', $ventaFuera->codigo_guia) }}" placeholder="Ej: EG07" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Guía</label>
                            <input type="text" name="numero_guia" value="{{ old('numero_guia', $ventaFuera->numero_guia) }}" placeholder="Ej: 00123" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cliente</label>
                            <input type="text" name="cliente" value="{{ old('cliente', $ventaFuera->cliente) }}" required placeholder="Nombre o Razón Social" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm uppercase">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Vendedor</label>
                            <select name="vendedor" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                                <option value="">Seleccione...</option>
                                <option value="Fredy C." {{ old('vendedor', $ventaFuera->vendedor) == 'Fredy C.' ? 'selected' : '' }}>Fredy C.</option>
                                <option value="Of. Huasqui" {{ old('vendedor', $ventaFuera->vendedor) == 'Of. Huasqui' ? 'selected' : '' }}>Of. Huasqui</option>
                                <option value="Daniel B." {{ old('vendedor', $ventaFuera->vendedor) == 'Daniel B.' ? 'selected' : '' }}>Daniel B.</option>
                                <option value="Procesos/Licitacio" {{ old('vendedor', $ventaFuera->vendedor) == 'Procesos/Licitacio' ? 'selected' : '' }}>Procesos/Licitacio</option>
                                <option value="Ing. Andi" {{ old('vendedor', $ventaFuera->vendedor) == 'Ing. Andi' ? 'selected' : '' }}>Ing. Andi</option>
                                <option value="Sr. Ricardo" {{ old('vendedor', $ventaFuera->vendedor) == 'Sr. Ricardo' ? 'selected' : '' }}>Sr. Ricardo</option>
                                <option value="Planta" {{ old('vendedor', $ventaFuera->vendedor) == 'Planta' ? 'selected' : '' }}>Planta</option>
                                <option value="Traslado" {{ old('vendedor', $ventaFuera->vendedor) == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Ing. Elias" {{ old('vendedor', $ventaFuera->vendedor) == 'Ing. Elias' ? 'selected' : '' }}>Ing. Elias</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Zona</label>
                            <select name="zona" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                                <option value="">Seleccione...</option>
                                <option value="Zona 1.1" {{ old('zona', $ventaFuera->zona) == 'Zona 1.1' ? 'selected' : '' }}>Zona 1.1</option>
                                <option value="Zona 1.2" {{ old('zona', $ventaFuera->zona) == 'Zona 1.2' ? 'selected' : '' }}>Zona 1.2</option>
                                <option value="Zona 2.1" {{ old('zona', $ventaFuera->zona) == 'Zona 2.1' ? 'selected' : '' }}>Zona 2.1</option>
                                <option value="Zona 2.2" {{ old('zona', $ventaFuera->zona) == 'Zona 2.2' ? 'selected' : '' }}>Zona 2.2</option>
                                <option value="Zona 2.3" {{ old('zona', $ventaFuera->zona) == 'Zona 2.3' ? 'selected' : '' }}>Zona 2.3</option>
                                <option value="Zona 2.4" {{ old('zona', $ventaFuera->zona) == 'Zona 2.4' ? 'selected' : '' }}>Zona 2.4</option>
                                <option value="Zona 3.1" {{ old('zona', $ventaFuera->zona) == 'Zona 3.1' ? 'selected' : '' }}>Zona 3.1</option>
                                <option value="Zona 3.2" {{ old('zona', $ventaFuera->zona) == 'Zona 3.2' ? 'selected' : '' }}>Zona 3.2</option>
                                <option value="Zona 3.3" {{ old('zona', $ventaFuera->zona) == 'Zona 3.3' ? 'selected' : '' }}>Zona 3.3</option>
                                <option value="Zona 3.4" {{ old('zona', $ventaFuera->zona) == 'Zona 3.4' ? 'selected' : '' }}>Zona 3.4</option>
                                <option value="Zona 3.5" {{ old('zona', $ventaFuera->zona) == 'Zona 3.5' ? 'selected' : '' }}>Zona 3.5</option>
                                <option value="Traslado" {{ old('zona', $ventaFuera->zona) == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lugar</label>
                            <input type="text" name="lugar" value="{{ old('lugar', $ventaFuera->lugar) }}" required placeholder="Ciudad o Destino" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm uppercase">
                        </div>
                    </div>

                    <div class="px-8 py-4 bg-slate-100 border-y border-slate-200">
                        <h4 class="font-bold text-slate-700 uppercase text-xs tracking-wider">Detalles del Producto y Totales</h4>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Producto</label>
                            <select name="producto" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                                <option value="">Seleccione un producto...</option>
                                <option value="Agrocal - Mix" {{ old('producto', $ventaFuera->producto) == 'Agrocal - Mix' ? 'selected' : '' }}>Agrocal - Mix</option>
                                <option value="Cal Bordalesa" {{ old('producto', $ventaFuera->producto) == 'Cal Bordalesa' ? 'selected' : '' }}>Cal Bordalesa</option>
                                <option value="Hidrocal" {{ old('producto', $ventaFuera->producto) == 'Hidrocal' ? 'selected' : '' }}>Hidrocal</option>
                                <option value="Roca Fosf.con marca" {{ old('producto', $ventaFuera->producto) == 'Roca Fosf.con marca' ? 'selected' : '' }}>Roca Fosf.con marca</option>
                                <option value="Sulfato de Calcio" {{ old('producto', $ventaFuera->producto) == 'Sulfato de Calcio' ? 'selected' : '' }}>Sulfato de Calcio</option>
                                <option value="Ulex-30" {{ old('producto', $ventaFuera->producto) == 'Ulex-30' ? 'selected' : '' }}>Ulex-30</option>
                                <option value="Super Magnocal - Mix" {{ old('producto', $ventaFuera->producto) == 'Super Magnocal - Mix' ? 'selected' : '' }}>Super Magnocal - Mix</option>
                                <option value="Nutri Abonaza" {{ old('producto', $ventaFuera->producto) == 'Nutri Abonaza' ? 'selected' : '' }}>Nutri Abonaza</option>
                                <option value="Agro Yeso" {{ old('producto', $ventaFuera->producto) == 'Agro Yeso' ? 'selected' : '' }}>Agro Yeso</option>
                                <option value="Dolomita" {{ old('producto', $ventaFuera->producto) == 'Dolomita' ? 'selected' : '' }}>Dolomita</option>
                                <option value="Abonaza Papero" {{ old('producto', $ventaFuera->producto) == 'Abonaza Papero' ? 'selected' : '' }}>Abonaza Papero</option>
                                <option value="Humus de Lom." {{ old('producto', $ventaFuera->producto) == 'Humus de Lom.' ? 'selected' : '' }}>Humus de Lom.</option>
                                <option value="Ulexita sin Marca" {{ old('producto', $ventaFuera->producto) == 'Ulexita sin Marca' ? 'selected' : '' }}>Ulexita sin Marca</option>
                                <option value="Gallinaza" {{ old('producto', $ventaFuera->producto) == 'Gallinaza' ? 'selected' : '' }}>Gallinaza</option>
                                <option value="Cal Nieve" {{ old('producto', $ventaFuera->producto) == 'Cal Nieve' ? 'selected' : '' }}>Cal Nieve</option>
                                <option value="Agrocal - Mix Tropical" {{ old('producto', $ventaFuera->producto) == 'Agrocal - Mix Tropical' ? 'selected' : '' }}>Agrocal - Mix Tropical</option>
                                <option value="Bio Cal" {{ old('producto', $ventaFuera->producto) == 'Bio Cal' ? 'selected' : '' }}>Bio Cal</option>
                                <option value="Yeso S/M" {{ old('producto', $ventaFuera->producto) == 'Yeso S/M' ? 'selected' : '' }}>Yeso S/M</option>
                                <option value="Carbon Mineral S/M" {{ old('producto', $ventaFuera->producto) == 'Carbon Mineral S/M' ? 'selected' : '' }}>Carbon Mineral S/M</option>
                                <option value="Filler 40 kg. S/M" {{ old('producto', $ventaFuera->producto) == 'Filler 40 kg. S/M' ? 'selected' : '' }}>Filler 40 kg. S/M</option>
                                <option value="Kal Hidratada-Hidro" {{ old('producto', $ventaFuera->producto) == 'Kal Hidratada-Hidro' ? 'selected' : '' }}>Kal Hidratada-Hidro</option>
                                <option value="Agrocal mix sulfocalcica" {{ old('producto', $ventaFuera->producto) == 'Agrocal mix sulfocalcica' ? 'selected' : '' }}>Agrocal mix sulfocalcica</option>
                                <option value="Carbocal 50kg" {{ old('producto', $ventaFuera->producto) == 'Carbocal 50kg' ? 'selected' : '' }}>Carbocal 50kg</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cantidad</label>
                            <input type="number" step="0.01" name="cantidad" x-model="cantidad" @input="calcularTotal()" value="{{ old('cantidad', $ventaFuera->cantidad) }}" required placeholder="0.00" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tonelada (Ton.)</label>
                            <input type="number" step="0.01" name="tonelada" value="{{ old('tonelada', $ventaFuera->tonelada) }}" placeholder="0.00" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm text-center">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">P. Unitario (S/)</label>
                            <input type="number" step="0.01" name="precio_unitario" x-model="precio" @input="calcularTotal()" value="{{ old('precio_unitario', $ventaFuera->precio_unitario) }}" required placeholder="0.00" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm font-bold text-right">
                        </div>
                        
                        <div class="flex items-center h-10 px-2">
                            <input type="hidden" name="con_igv" value="0">
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="checkbox" name="con_igv" value="1" {{ old('con_igv', $ventaFuera->con_igv) ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold text-slate-600">INCLUYE IGV</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Total Facturado (Calculado Auto)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-indigo-700 font-black">S/</span>
                                <input type="number" step="0.01" name="total_facturado" x-model="total_facturado" required readonly class="w-full pl-9 rounded-lg border-indigo-300 bg-indigo-50 focus:border-indigo-500 focus:ring-indigo-200 text-lg font-black text-indigo-700 text-right cursor-not-allowed">
                            </div>
                        </div>

                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Detalle / Observaciones (Opcional)</label>
                            <input type="text" name="detalle" value="{{ old('detalle', $ventaFuera->detalle) }}" placeholder="Alguna nota adicional..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm italic uppercase">
                        </div>
                    </div>

                    <div class="px-8 py-4 bg-slate-100 border-y border-slate-200">
                        <h4 class="font-bold text-slate-700 uppercase text-xs tracking-wider">Condiciones de Pago</h4>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                        <div>
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Forma de Pago</label>
                             <select name="forma_pago" x-model="forma_pago" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm font-bold text-slate-700">
                                <option value="Contado">Contado</option>
                                <option value="Credito">Crédito</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                        </div>

                        <div x-show="forma_pago === 'Credito' || forma_pago === 'Mixto'" x-transition style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Plazo Crédito</label>
                            <select name="plazo_dias" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm font-bold text-red-600 bg-red-50">
                                <option value="15" {{ old('plazo_dias', $ventaFuera->plazo_dias) == '15' ? 'selected' : '' }}>15 Días</option>
                                <option value="30" {{ old('plazo_dias', $ventaFuera->plazo_dias) == '30' ? 'selected' : '' }}>30 Días</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Contado (S/)</label>
                            <input type="number" step="0.01" name="monto_contado" value="{{ old('monto_contado', $ventaFuera->monto_contado) }}" placeholder="0.00" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Crédito (S/)</label>
                            <input type="number" step="0.01" name="monto_credito" value="{{ old('monto_credito', $ventaFuera->monto_credito) }}" placeholder="0.00" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Destino Dinero</label>
                             <select name="movimiento" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-200 text-sm font-bold text-slate-700">
                                <option value="CAJA" {{ old('movimiento', $ventaFuera->movimiento) == 'CAJA' ? 'selected' : '' }}>💵 CAJA EFECTIVO</option>
                                <option value="DEPOSITO" {{ old('movimiento', $ventaFuera->movimiento) == 'DEPOSITO' ? 'selected' : '' }}>🏦 BANCO / DEPÓSITO</option>
                            </select>
                        </div>
                     </div>

                    <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-4">
                        <a href="{{ route('ventas.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-bold hover:bg-white transition text-sm shadow-sm">Cancelar</a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Actualizar Venta Externa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ventaExternaForm', () => ({
                cantidad: '{{ old('cantidad', $ventaFuera->cantidad) }}',
                precio: '{{ old('precio_unitario', $ventaFuera->precio_unitario) }}',
                total_facturado: '{{ old('total_facturado', $ventaFuera->total_facturado) }}',
                forma_pago: '{{ old('forma_pago', $ventaFuera->forma_pago ?? 'Contado') }}',

                calcularTotal() {
                    let c = parseFloat(this.cantidad) || 0;
                    let p = parseFloat(this.precio) || 0;
                    this.total_facturado = (c * p).toFixed(2);
                }
            }));
        });
    </script>
</x-app-layout>