<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                Editar Asiento Contable
            </h2>
            <a href="{{ route('egresos.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                ← Volver a Egresos
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any() || session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative font-bold mb-6 shadow-sm">
                    <p class="mb-1">🚨 Por favor, corrige lo siguiente:</p>
                    <ul class="list-disc pl-5 text-xs font-medium">
                        @if(session('error')) <li>{{ session('error') }}</li> @endif
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-slate-200">
                
                <div class="bg-slate-800 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-white font-bold text-sm uppercase tracking-wider">Modificando Operación</h3>
                        <p class="text-slate-400 text-xs mt-1">Actualice la información del comprobante de pago.</p>
                    </div>
                    <div class="bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded">ERP Pacífico</div>
                </div>

                <div class="p-8">
                    @php
                        $isNoGravado = $egreso->no_gravado > 0;
                        $isTC = $egreso->otras_tasas > 1;
                        $montoCalculado = $isTC ? ($egreso->total / $egreso->otras_tasas) : $egreso->total;
                        $hasDetraccion = $egreso->detraccion_monto > 0;
                        $porcDetraccion = ($egreso->total > 0 && $hasDetraccion) ? round($egreso->detraccion_monto / $egreso->total, 3) : 0;
                    @endphp

                    <form action="{{ route('egresos.update', $egreso->id) }}" method="POST" id="formCalculadora" x-data="buscadorProveedores('{{ old('ruc', $egreso->ruc) }}', '{{ old('razon_social', $egreso->razon_social) }}')">
                        @csrf
                        @method('PUT')

                        <datalist id="lista_rucs">
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->ruc }}">{{ $prov->razon_social }}</option>
                            @endforeach
                        </datalist>

                        <datalist id="lista_razones">
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->razon_social }}">{{ $prov->ruc }}</option>
                            @endforeach
                        </datalist>

                        <div class="mb-8">
                            <h4 class="text-indigo-700 font-bold text-sm uppercase tracking-wide border-b border-indigo-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                Información General
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Emisión *</label>
                                    <input type="date" name="fecha" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition-all" value="{{ old('fecha', $egreso->fecha_emision ? \Carbon\Carbon::parse($egreso->fecha_emision)->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>

                                <div class="md:col-span-9">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Comprobante (Tabla 10 SUNAT) *</label>
                                    <select name="descripcion" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition-all">
                                        <option value="00 - Otros" {{ old('descripcion', $egreso->descripcion) == '00 - Otros' ? 'selected' : '' }}>00 - Otros</option>
                                        <option value="01 - Factura" {{ old('descripcion', $egreso->descripcion) == '01 - Factura' ? 'selected' : '' }}>01 - Factura</option>
                                        <option value="02 - Recibo por Honorarios" {{ old('descripcion', $egreso->descripcion) == '02 - Recibo por Honorarios' ? 'selected' : '' }}>02 - Recibo por Honorarios</option>
                                        <option value="03 - Boleta de Venta" {{ old('descripcion', $egreso->descripcion) == '03 - Boleta de Venta' ? 'selected' : '' }}>03 - Boleta de Venta</option>
                                        <option value="04 - Liquidación de compra" {{ old('descripcion', $egreso->descripcion) == '04 - Liquidación de compra' ? 'selected' : '' }}>04 - Liquidación de compra</option>
                                        <option value="05 - Boleto de compañía de aviación comercial por el servicio de transporte aéreo de pasajeros" {{ old('descripcion', $egreso->descripcion) == '05 - Boleto de compañía de aviación comercial por el servicio de transporte aéreo de pasajeros' ? 'selected' : '' }}>05 - Boleto de aviación comercial</option>
                                        <option value="06 - Carta de porte aéreo por el servicio de transporte de carga aérea" {{ old('descripcion', $egreso->descripcion) == '06 - Carta de porte aéreo por el servicio de transporte de carga aérea' ? 'selected' : '' }}>06 - Carta de porte aéreo</option>
                                        <option value="07 - Nota de crédito" {{ old('descripcion', $egreso->descripcion) == '07 - Nota de crédito' ? 'selected' : '' }}>07 - Nota de crédito</option>
                                        <option value="08 - Nota de débito" {{ old('descripcion', $egreso->descripcion) == '08 - Nota de débito' ? 'selected' : '' }}>08 - Nota de débito</option>
                                        <option value="09 - Guía de remisión - Remitente" {{ old('descripcion', $egreso->descripcion) == '09 - Guía de remisión - Remitente' ? 'selected' : '' }}>09 - Guía de remisión - Remitente</option>
                                        <option value="10 - Recibo por Arrendamiento" {{ old('descripcion', $egreso->descripcion) == '10 - Recibo por Arrendamiento' ? 'selected' : '' }}>10 - Recibo por Arrendamiento</option>
                                        <option value="12 - Ticket o cinta emitido por máquina registradora" {{ old('descripcion', $egreso->descripcion) == '12 - Ticket o cinta emitido por máquina registradora' ? 'selected' : '' }}>12 - Ticket o cinta emitido por máquina registradora</option>
                                        <option value="14 - Recibo por servicios públicos de suministro de energía eléctrica, agua, teléfono, telex y telegráficos" {{ str_contains(old('descripcion', $egreso->descripcion), '14 - Recibo') ? 'selected' : '' }}>14 - Recibo por servicios públicos</option>
                                        <option value="20 - Comprobante de Retención" {{ old('descripcion', $egreso->descripcion) == '20 - Comprobante de Retención' ? 'selected' : '' }}>20 - Comprobante de Retención</option>
                                        </select>
                                </div>

                                <div class="md:col-span-8">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Glosa / Detalle</label>
                                    <textarea name="glosa" rows="1" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm placeholder-slate-300 transition-all uppercase" placeholder="Ej: Compra de materiales...">{{ old('glosa', $egreso->glosa) }}</textarea>
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Responsable</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        </span>
                                        <input type="text" name="responsable" value="{{ old('responsable', $egreso->responsable) }}" class="w-full pl-9 rounded-lg border-slate-300 bg-white text-slate-700 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all uppercase">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-slate-700 font-bold text-sm uppercase tracking-wide border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                Datos del Proveedor
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">RUC *</label>
                                    <input type="text" name="ruc" id="ruc" x-model="ruc" @input="buscar()" list="lista_rucs" autocomplete="off" maxlength="11" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm font-mono" placeholder="20XXXXXXXXX">
                                    
                                    <span x-show="buscando" class="text-[10px] text-indigo-600 font-bold mt-1 block tracking-tight" style="display: none;">Buscando...</span>
                                    <span x-show="mensajeExito" class="text-[10px] text-emerald-600 font-bold mt-1 block tracking-tight" style="display: none;">¡Proveedor encontrado!</span>
                                </div>

                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Razón Social *</label>
                                    <input type="text" name="razon_social" id="razon_social" x-model="razon_social" list="lista_razones" autocomplete="off" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition-colors duration-300 uppercase" placeholder="Nombre de la empresa...">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Serie - Número</label>
                                    <input type="text" name="n_comprobante" value="{{ old('n_comprobante', $egreso->n_comprobante) }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm uppercase" placeholder="F001-00001">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Guía</label>
                                    <input type="text" name="guia" value="{{ old('guia', $egreso->guia) }}" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Estado Guía</label>
                                    <input type="text" name="estado_guia" value="{{ old('estado_guia', $egreso->estado_guia) }}" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ref. NC/ND</label>
                                    <input type="text" name="nc_nd" value="{{ old('nc_nd', $egreso->nc_nd) }}" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 bg-indigo-50/50 p-6 rounded-xl border border-indigo-100">
                            <h4 class="text-indigo-800 font-bold text-sm uppercase tracking-wide border-b border-indigo-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-indigo-200 text-indigo-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                Importes y Moneda
                            </h4>

                            <div class="flex flex-wrap gap-8 mb-6 items-end">
                                <label class="inline-flex items-center cursor-pointer group mb-2">
                                    <input type="checkbox" id="checkNoGravado" name="check_no_gravado" {{ old('check_no_gravado', $isNoGravado) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5 transition-all">
                                    <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition">Operación No Gravada (Sin IGV)</span>
                                </label>

                                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" id="checkTC" name="check_tc" {{ old('check_tc', $isTC) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5 transition-all">
                                        <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition">Pago en Dólares (USD)</span>
                                    </label>
                                    
                                    <div id="divTasaCambio" class="hidden flex items-center gap-2 border-l border-slate-200 pl-3 ml-2 transition-all">
                                        <span class="text-xs font-bold text-slate-500">T.C.:</span>
                                        <input type="number" step="0.001" name="tasa_cambio" id="inputTasaCambio" value="{{ old('tasa_cambio', $isTC ? $egreso->otras_tasas : '3.750') }}" 
                                               class="w-20 py-1 px-2 text-sm font-bold text-indigo-700 border border-indigo-300 rounded focus:ring-indigo-500 focus:border-indigo-500 shadow-inner">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                                <div class="bg-white p-3 rounded-lg border border-indigo-200 shadow-sm">
                                    <label class="block text-indigo-800 font-extrabold text-xs mb-1">MONTO INGRESADO</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-indigo-600 font-bold" id="simboloMoneda">S/</span>
                                        <input type="number" step="0.01" name="monto_ingresado" id="inputMonto" required 
                                               class="w-full pl-8 border-indigo-300 ring-2 ring-indigo-50 rounded-md text-xl font-bold text-right text-indigo-900 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                               value="{{ old('monto_ingresado', number_format($montoCalculado, 2, '.', '')) }}" placeholder="0.00">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1 text-right">Base Imponible</label>
                                    <input type="text" name="base_imponible" id="inputBase" readonly value="{{ old('base_imponible', $egreso->base_imponible) }}" class="w-full bg-slate-100 border-slate-200 rounded-lg text-sm text-right font-mono text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1 text-right">IGV (18%)</label>
                                    <input type="text" name="igv" id="inputIGV" readonly value="{{ old('igv', $egreso->igv) }}" class="w-full bg-slate-100 border-slate-200 rounded-lg text-sm text-right font-mono text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1 text-right">TOTAL FINAL (S/)</label>
                                    <input type="text" name="total_final" id="inputTotalFinal" readonly value="{{ old('total_final', $egreso->total) }}" class="w-full bg-slate-800 border-slate-800 rounded-lg text-lg text-right font-bold text-white shadow-md">
                                    <input type="hidden" name="monto_no_gravado" id="hiddenNoGravado" value="{{ old('monto_no_gravado', $egreso->no_gravado) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 border border-yellow-200 rounded-xl p-6 bg-yellow-50/30">
                            <div class="flex items-center gap-3 mb-4 border-b border-yellow-200 pb-2">
                                <input type="checkbox" id="checkDetraccion" {{ old('checkDetraccion', $hasDetraccion) ? 'checked' : '' }} class="rounded border-yellow-400 text-yellow-600 shadow-sm w-5 h-5 focus:ring-yellow-500 transition-all">
                                <h4 class="text-yellow-800 font-bold text-sm uppercase tracking-wide">Aplicar Detracción (SPOT)</h4>
                            </div>
                            <div id="panelDetraccion" class="grid grid-cols-1 md:grid-cols-3 gap-6 opacity-50 pointer-events-none transition-opacity duration-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Servicio (%)</label>
                                    <select id="selectPorcentajeDetraccion" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-all">
                                        <option value="0.015" {{ abs($porcDetraccion - 0.015) < 0.001 ? 'selected' : '' }}>1.5% - Insumos Agrícolas</option>
                                        <option value="0.04" {{ abs($porcDetraccion - 0.04) < 0.001 ? 'selected' : '' }}>4.00% - Transporte / Mov. Tierra</option>
                                        <option value="0.09" {{ abs($porcDetraccion - 0.09) < 0.001 ? 'selected' : '' }}>9.00% - Otros Bienes</option>
                                        <option value="0.10" {{ abs($porcDetraccion - 0.10) < 0.001 ? 'selected' : '' }}>10.00% - Alquileres / Arena</option>
                                        <option value="0.12" {{ abs($porcDetraccion - 0.12) < 0.001 ? 'selected' : '' }}>12.00% - Mantenimiento</option>
                                        <option value="0.15" {{ abs($porcDetraccion - 0.15) < 0.001 ? 'selected' : '' }}>15.00% - Residuos / Chatarra</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Detracción (S/)</label>
                                    <input type="text" name="detraccion_monto" id="inputMontoDetraccion" readonly value="{{ old('detraccion_monto', $egreso->detraccion_monto > 0 ? $egreso->detraccion_monto : '') }}" class="w-full bg-white border-slate-300 rounded-lg text-sm text-right font-bold text-red-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Pago Detr.</label>
                                    <input type="date" name="detraccion_fecha" value="{{ old('detraccion_fecha', $egreso->detraccion_fecha ? \Carbon\Carbon::parse($egreso->detraccion_fecha)->format('Y-m-d') : '') }}" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-slate-700 font-bold text-sm uppercase tracking-wide border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                Tesorería y Pagos
                            </h4>
                            
                            <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-lg mb-6">
                                <label class="block text-sm font-bold text-blue-800 mb-3 uppercase tracking-wide">¿Cómo se realizó el pago?</label>
                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center cursor-pointer hover:bg-white p-2 rounded-lg transition shadow-sm border border-transparent hover:border-slate-200">
                                        <input type="radio" name="metodo_pago" value="Efectivo" {{ old('metodo_pago', $egreso->metodo_pago) == 'Efectivo' ? 'checked' : '' }} class="form-radio text-green-600 h-5 w-5 focus:ring-green-500" required>
                                        <span class="ml-2 font-bold text-slate-700 flex items-center gap-1">
                                            💵 Efectivo
                                        </span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer hover:bg-white p-2 rounded-lg transition shadow-sm border border-transparent hover:border-slate-200">
                                        <input type="radio" name="metodo_pago" value="Digital" {{ old('metodo_pago', $egreso->metodo_pago) == 'Digital' ? 'checked' : '' }} class="form-radio text-blue-600 h-5 w-5 focus:ring-blue-500" required>
                                        <span class="ml-2 font-bold text-slate-700 flex items-center gap-1">
                                            📱 Digital (Transf. / Yape / Plin)
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Estado del Pago</label>
                                    <select name="estado_pago" id="selectEstado" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm font-medium transition-all">
                                        <option value="PENDIENTE" {{ old('estado_pago', $egreso->estado_pago) == 'PENDIENTE' ? 'selected' : '' }}>🔴 PENDIENTE (Por Pagar)</option>
                                        <option value="CANCELADO" {{ old('estado_pago', $egreso->estado_pago) == 'CANCELADO' ? 'selected' : '' }}>🟢 CANCELADO (Pagado)</option>
                                        <option value="ANULADO" {{ old('estado_pago', $egreso->estado_pago) == 'ANULADO' ? 'selected' : '' }}>⚫ ANULADO</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Saldo Pendiente (S/)</label>
                                    <input type="number" step="0.01" name="saldo_pendiente" id="inputSaldo" value="{{ old('saldo_pendiente', $egreso->saldo_pendiente) }}" class="w-full rounded-lg border-slate-300 bg-white text-sm text-right font-extrabold text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 border-t border-slate-100">
                            <a href="{{ route('egresos.index') }}" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg shadow-sm hover:bg-slate-50 transition">Cancelar</a>
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition transform hover:-translate-y-0.5">💾 Actualizar Operación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('buscadorProveedores', (initialRuc = '', initialRazon = '') => ({
                ruc: initialRuc,
                razon_social: initialRazon,
                buscando: false,
                mensajeExito: false,

                async buscar() {
                    if (this.ruc.length === 11 || this.ruc.length === 8) {
                        this.buscando = true;
                        this.mensajeExito = false;

                        try {
                            let respuesta = await fetch(`/egresos/buscar-ruc?ruc=${this.ruc}`);
                            let datos = await respuesta.json();

                            if (datos.encontrado) {
                                this.razon_social = datos.razon_social;
                                this.mensajeExito = true;
                            }
                        } catch (error) {
                            console.error("Error al buscar el RUC:", error);
                        } finally {
                            this.buscando = false;
                        }
                    } else {
                        this.mensajeExito = false;
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const rucInput = document.getElementById('ruc');
            const razonSocialInput = document.getElementById('razon_social');
            
            if(razonSocialInput && rucInput) {
                razonSocialInput.addEventListener('change', function() {
                    let opciones = document.getElementById('lista_razones').options;
                    for (let i = 0; i < opciones.length; i++) {
                        if (opciones[i].value === this.value) {
                            rucInput.value = opciones[i].text;
                            rucInput.dispatchEvent(new Event('input'));
                            break;
                        }
                    }
                });
            }

            const checkNoGravado = document.getElementById('checkNoGravado');
            const checkTC = document.getElementById('checkTC');
            const divTasaCambio = document.getElementById('divTasaCambio');
            const inputTasaCambio = document.getElementById('inputTasaCambio');
            const inputMonto = document.getElementById('inputMonto');
            const simboloMoneda = document.getElementById('simboloMoneda');
            const inputBase = document.getElementById('inputBase');
            const inputIGV = document.getElementById('inputIGV');
            const inputTotalFinal = document.getElementById('inputTotalFinal');
            const hiddenNoGravado = document.getElementById('hiddenNoGravado');
            const checkDetraccion = document.getElementById('checkDetraccion');
            const panelDetraccion = document.getElementById('panelDetraccion');
            const selectPorcentaje = document.getElementById('selectPorcentajeDetraccion');
            const inputMontoDetraccion = document.getElementById('inputMontoDetraccion');
            const selectEstado = document.getElementById('selectEstado');
            const inputSaldo = document.getElementById('inputSaldo');

            // Inicializar visibilidad según datos cargados desde la BD
            if (checkDetraccion.checked) {
                panelDetraccion.classList.remove('opacity-50', 'pointer-events-none');
            }
            if (checkTC.checked) {
                divTasaCambio.classList.remove('hidden');
                simboloMoneda.innerText = "$";
                simboloMoneda.classList.add('text-orange-500');
            }
            if (selectEstado.value === 'CANCELADO' || selectEstado.value === 'ANULADO') {
                inputSaldo.readOnly = true;
                inputSaldo.classList.add('bg-slate-100', 'text-slate-400');
                inputSaldo.classList.remove('bg-white', 'text-red-600');
            }

            function calcular() {
                let monto = parseFloat(inputMonto.value) || 0;
                let tasa = parseFloat(inputTasaCambio.value) || 3.75;

                if (checkTC.checked) {
                    monto = monto * tasa;
                    simboloMoneda.innerText = "$";
                    simboloMoneda.classList.add('text-orange-500');
                    divTasaCambio.classList.remove('hidden');
                } else {
                    simboloMoneda.innerText = "S/";
                    simboloMoneda.classList.remove('text-orange-500');
                    divTasaCambio.classList.add('hidden');
                }

                let base = 0;
                let igv = 0;
                let total = monto;

                if (checkNoGravado.checked) {
                    base = 0;
                    igv = 0;
                    inputBase.value = "0.00";
                    inputIGV.value = "0.00";
                    inputBase.classList.add('text-slate-300');
                    inputIGV.classList.add('text-slate-300');
                    hiddenNoGravado.value = total.toFixed(2);
                } else {
                    base = total / 1.18;
                    igv = total - base;
                    inputBase.classList.remove('text-slate-300');
                    inputIGV.classList.remove('text-slate-300');
                    inputBase.value = base.toFixed(2);
                    inputIGV.value = igv.toFixed(2);
                    hiddenNoGravado.value = "0";
                }

                inputTotalFinal.value = total.toFixed(2);

                if (checkDetraccion.checked) {
                    let porcentaje = parseFloat(selectPorcentaje.value);
                    let detraccion = total * porcentaje;
                    inputMontoDetraccion.value = detraccion.toFixed(2);
                }

                if (selectEstado.value === 'PENDIENTE') {
                    inputSaldo.value = total.toFixed(2);
                }
            }

            inputMonto.addEventListener('input', calcular);
            inputTasaCambio.addEventListener('input', calcular);
            checkTC.addEventListener('change', calcular);
            checkNoGravado.addEventListener('change', calcular);
            
            checkDetraccion.addEventListener('change', function() {
                if (this.checked) {
                    panelDetraccion.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    panelDetraccion.classList.add('opacity-50', 'pointer-events-none');
                    inputMontoDetraccion.value = '';
                }
                calcular();
            });

            selectPorcentaje.addEventListener('change', calcular);

            selectEstado.addEventListener('change', function() {
                if (this.value === 'CANCELADO' || this.value === 'ANULADO') {
                    inputSaldo.value = '0.00';
                    inputSaldo.readOnly = true;
                    inputSaldo.classList.add('bg-slate-100', 'text-slate-400');
                    inputSaldo.classList.remove('bg-white', 'text-red-600');
                } else {
                    inputSaldo.readOnly = false;
                    inputSaldo.classList.remove('bg-slate-100', 'text-slate-400');
                    inputSaldo.classList.add('bg-white', 'text-red-600');
                    let totalActual = parseFloat(inputTotalFinal.value) || 0;
                    inputSaldo.value = totalActual.toFixed(2);
                }
            });
        });
    </script>
</x-app-layout>