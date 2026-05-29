<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-300 pb-2 gap-2">
            <h2 class="font-bold text-lg text-slate-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Gestión de Operaciones Comerciales
            </h2>
            <div class="text-xs font-bold text-slate-700 uppercase bg-indigo-50 px-3 py-1 rounded-sm border border-indigo-100">
                Periodo: <span class="text-indigo-700">{{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }}</span> al <span class="text-indigo-700">{{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 bg-slate-50 min-h-screen" x-data="confirmModal()">
        <div class="max-w-[99%] mx-auto space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-900 px-3 py-1.5 text-[11px] font-bold uppercase rounded-sm mt-2">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-900 px-3 py-1.5 text-[11px] font-bold uppercase rounded-sm mt-2">
                    🚨 {{ session('error') }}
                </div>
            @endif

            <div>
                <div class="flex flex-col xl:flex-row justify-between xl:items-end mb-1 gap-2">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-1.5">
                            <div class="w-1.5 h-3 bg-indigo-700"></div> Libro de Ventas Internas
                        </h3>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mt-2 xl:mt-0">
                        <form action="{{ route('ventas.index') }}" method="GET" class="flex border border-slate-400 rounded-sm overflow-hidden bg-white shadow-sm items-center">
                            <input type="hidden" name="fecha_inicio_fuera" value="{{ $fecha_inicio_fuera }}">
                            <input type="hidden" name="fecha_fin_fuera" value="{{ $fecha_fin_fuera }}">
                            
                            <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1.5 flex items-center border-r border-slate-300 uppercase">Desde</span>
                            <input type="date" name="fecha_inicio" value="{{ $fecha_inicio }}" class="text-[10px] border-0 py-1 px-2 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50">
                            
                            <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1.5 flex items-center border-l border-r border-slate-300 uppercase">Hasta</span>
                            <input type="date" name="fecha_fin" value="{{ $fecha_fin }}" class="text-[10px] border-0 py-1 px-2 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50">

                            <select name="movimiento" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase">
                                <option value="todos" {{ $movimiento == 'todos' ? 'selected' : '' }}>Todos (Destino)</option>
                                <option value="CAJA" {{ $movimiento == 'CAJA' ? 'selected' : '' }}>Caja Físico</option>
                                <option value="DEPOSITO" {{ $movimiento == 'DEPOSITO' ? 'selected' : '' }}>Depósito</option>
                            </select>

                            <select name="vendedor" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase">
                                <option value="todos" {{ $vendedor == 'todos' ? 'selected' : '' }}>Todos (Vend.)</option>
                                <option value="Fredy C." {{ $vendedor == 'Fredy C.' ? 'selected' : '' }}>Fredy C.</option>
                                <option value="Of. Huasqui" {{ $vendedor == 'Of. Huasqui' ? 'selected' : '' }}>Of. Huasqui</option>
                                <option value="Daniel B." {{ $vendedor == 'Daniel B.' ? 'selected' : '' }}>Daniel B.</option>
                                <option value="Procesos/Licitacio" {{ $vendedor == 'Procesos/Licitacio' ? 'selected' : '' }}>Procesos/Licitacio</option>
                                <option value="Ing. Andi" {{ $vendedor == 'Ing. Andi' ? 'selected' : '' }}>Ing. Andi</option>
                                <option value="Sr. Ricardo" {{ $vendedor == 'Sr. Ricardo' ? 'selected' : '' }}>Sr. Ricardo</option>
                                <option value="Planta" {{ $vendedor == 'Planta' ? 'selected' : '' }}>Planta</option>
                                <option value="Traslado" {{ $vendedor == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Ing. Elias" {{ $vendedor == 'Ing. Elias' ? 'selected' : '' }}>Ing. Elias</option>
                            </select>

                            <select name="producto" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase max-w-[130px]">
                                <option value="todos" {{ $producto_filtro == 'todos' ? 'selected' : '' }}>Todos (Prod.)</option>
                                <option value="Agrocal - Mix" {{ $producto_filtro == 'Agrocal - Mix' ? 'selected' : '' }}>Agrocal - Mix</option>
                                <option value="Cal Bordalesa" {{ $producto_filtro == 'Cal Bordalesa' ? 'selected' : '' }}>Cal Bordalesa</option>
                                <option value="Hidrocal" {{ $producto_filtro == 'Hidrocal' ? 'selected' : '' }}>Hidrocal</option>
                                <option value="Roca Fosf.con marca" {{ $producto_filtro == 'Roca Fosf.con marca' ? 'selected' : '' }}>Roca Fosf.con marca</option>
                                <option value="Sulfato de Calcio" {{ $producto_filtro == 'Sulfato de Calcio' ? 'selected' : '' }}>Sulfato de Calcio</option>
                                <option value="Ulex-30" {{ $producto_filtro == 'Ulex-30' ? 'selected' : '' }}>Ulex-30</option>
                                <option value="Super Magnocal - Mix" {{ $producto_filtro == 'Super Magnocal - Mix' ? 'selected' : '' }}>Super Magnocal - Mix</option>
                                <option value="Nutri Abonaza" {{ $producto_filtro == 'Nutri Abonaza' ? 'selected' : '' }}>Nutri Abonaza</option>
                                <option value="Agro Yeso" {{ $producto_filtro == 'Agro Yeso' ? 'selected' : '' }}>Agro Yeso</option>
                                <option value="Dolomita" {{ $producto_filtro == 'Dolomita' ? 'selected' : '' }}>Dolomita</option>
                                <option value="Abonaza Papero" {{ $producto_filtro == 'Abonaza Papero' ? 'selected' : '' }}>Abonaza Papero</option>
                                <option value="Humus de Lom." {{ $producto_filtro == 'Humus de Lom.' ? 'selected' : '' }}>Humus de Lom.</option>
                                <option value="Ulexita sin Marca" {{ $producto_filtro == 'Ulexita sin Marca' ? 'selected' : '' }}>Ulexita sin Marca</option>
                                <option value="Gallinaza" {{ $producto_filtro == 'Gallinaza' ? 'selected' : '' }}>Gallinaza</option>
                                <option value="Cal Nieve" {{ $producto_filtro == 'Cal Nieve' ? 'selected' : '' }}>Cal Nieve</option>
                                <option value="Agrocal - Mix Tropical" {{ $producto_filtro == 'Agrocal - Mix Tropical' ? 'selected' : '' }}>Agrocal - Mix Tropical</option>
                                <option value="Bio Cal" {{ $producto_filtro == 'Bio Cal' ? 'selected' : '' }}>Bio Cal</option>
                                <option value="Yeso S/M" {{ $producto_filtro == 'Yeso S/M' ? 'selected' : '' }}>Yeso S/M</option>
                                <option value="Carbon Mineral S/M" {{ $producto_filtro == 'Carbon Mineral S/M' ? 'selected' : '' }}>Carbon Mineral S/M</option>
                                <option value="Filler 40 kg. S/M" {{ $producto_filtro == 'Filler 40 kg. S/M' ? 'selected' : '' }}>Filler 40 kg. S/M</option>
                                <option value="Kal Hidratada-Hidro" {{ $producto_filtro == 'Kal Hidratada-Hidro' ? 'selected' : '' }}>Kal Hidratada-Hidro</option>
                                <option value="Agrocal mix sulfocalcica" {{ $producto_filtro == 'Agrocal mix sulfocalcica' ? 'selected' : '' }}>Agrocal mix sulfocalcica</option>
                                <option value="Carbocal 50kg" {{ $producto_filtro == 'Carbocal 50kg' ? 'selected' : '' }}>Carbocal 50kg</option>
                            </select>
                            
                            <button type="submit" class="bg-slate-800 hover:bg-black text-white px-3 py-1.5 text-[10px] font-bold uppercase transition border-l border-slate-400">Filtrar</button>
                        </form>

                        <a href="{{ route('ventas.create') }}" class="flex items-center gap-1 bg-indigo-700 hover:bg-indigo-800 text-white px-3 py-1.5 rounded-sm text-[10px] font-bold uppercase transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Registrar Venta
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar border border-slate-400 bg-white shadow-sm">
                    <table class="w-full text-left text-[11px] whitespace-nowrap">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider border-b-2 border-slate-600">
                            <tr>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-20">Fecha</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-32">Documento</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 min-w-[180px]">Cliente / Dirección</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-32">Vendedor / Zona</th>
                                <th class="px-2 py-1.5 border-r border-slate-700">Detalle Mercadería</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 text-center w-28">Retención</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 text-right w-36">Liquidación</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 text-right w-32 bg-slate-800">Total</th>
                                <th class="px-2 py-1.5 text-center w-16 bg-slate-800">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-300 text-slate-800">
                            @forelse($ventas as $venta)
                            <tr class="hover:bg-slate-100 transition-colors">
                                
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 font-bold">
                                    {{ $venta->fecha->format('d/m/y') }}
                                </td>

                                <td class="px-2 py-1.5 align-top border-r border-slate-300">
                                    <div class="font-black uppercase">{{ $venta->numero_comprobante }}</div>
                                    <div class="text-[9px] text-slate-500 uppercase">{{ $venta->tipo_comprobante }}</div>
                                    @if($venta->codigo_guia || $venta->numero_guia)
                                        <div class="text-[9px] text-indigo-700 font-bold mt-0.5">Guía: {{ $venta->codigo_guia }} {{ $venta->numero_guia }}</div>
                                    @endif
                                </td>

                                <td class="px-2 py-1.5 align-top border-r border-slate-300 whitespace-normal">
                                    <div class="font-bold leading-tight">{{ $venta->cliente }}</div>
                                    <div class="text-[9px] text-slate-600 uppercase mt-0.5">{{ $venta->lugar ?: 'Sin Ubicación' }}</div>
                                    @if($venta->detalle)
                                        <div class="text-[9px] text-slate-600 mt-0.5 italic">Nota: {{ $venta->detalle }}</div>
                                    @endif
                                </td>

                                <td class="px-2 py-1.5 align-top border-r border-slate-300">
                                    <div class="font-bold">{{ $venta->vendedor }}</div>
                                    <div class="text-[9px] text-slate-600 uppercase">{{ $venta->zona }}</div>
                                </td>

                                <td class="px-2 py-1.5 align-top border-r border-slate-300 p-0">
                                    @if($venta->detalles->count() > 0)
                                        <div class="w-full">
                                            @foreach($venta->detalles as $loopIndex => $detalle)
                                                <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center py-0.5 px-1 {{ !$loop->last ? 'border-b border-slate-200' : '' }}">
                                                    <span class="font-bold uppercase text-[10px] truncate" title="{{ $detalle->producto ? $detalle->producto->nombre : 'Sin Nombre' }}">
                                                        - {{ $detalle->producto ? $detalle->producto->nombre : 'Sin Nombre' }}
                                                    </span>
                                                    <div class="flex items-center gap-2 text-[9px] ml-1 xl:ml-0 whitespace-nowrap">
                                                        <span>Cant: <span class="font-bold text-slate-900">{{ number_format($detalle->cantidad, 2) }}</span></span>
                                                        <span class="text-slate-300">|</span>
                                                        <span>Ton: <span class="font-bold text-indigo-700">{{ number_format($detalle->tonelada ?? 0, 2) }}</span></span>
                                                        <span class="text-slate-300">|</span>
                                                        <span>P.U: <span class="font-bold text-emerald-700">S/{{ number_format($detalle->precio_unitario, 2) }}</span></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-slate-400 italic px-1">Sin productos</div>
                                    @endif
                                </td>

                                <td class="px-2 py-1.5 align-top text-center border-r border-slate-300 bg-orange-50/50">
                                    @if($venta->aplica_detraccion && $venta->monto_detraccion > 0)
                                        <div class="font-black text-red-700">S/ {{ number_format($venta->monto_detraccion, 2) }}</div>
                                        <div class="text-[9px] font-bold text-orange-900 uppercase">SPOT: {{ number_format($venta->tipo_detraccion, 1) }}%</div>
                                        @if($venta->fecha_pago_detraccion)
                                            <div class="text-[8px] text-slate-600 font-bold mt-0.5">Pagado: {{ \Carbon\Carbon::parse($venta->fecha_pago_detraccion)->format('d/m/y') }}</div>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-bold">-</span>
                                    @endif
                                </td>

                                <td class="px-2 py-1.5 align-top text-right border-r border-slate-300">
                                    <div class="text-[9px] font-black uppercase tracking-wider mb-0.5 {{ $venta->forma_pago == 'Contado' ? 'text-emerald-700' : ($venta->forma_pago == 'Credito' ? 'text-orange-700' : 'text-purple-700') }}">
                                        {{ $venta->forma_pago }}
                                    </div>
                                    @if($venta->monto_contado > 0) 
                                        <div class="text-[9px] text-slate-700">Efectivo: <span class="font-bold text-slate-900">S/{{number_format($venta->monto_contado, 2)}}</span></div> 
                                    @endif
                                    @if($venta->monto_credito > 0) 
                                        <div class="text-[9px] text-slate-700">Crédito: <span class="font-bold text-slate-900">S/{{number_format($venta->monto_credito, 2)}}</span></div> 
                                    @endif
                                    <div class="text-[8px] font-bold text-slate-500 uppercase mt-1">
                                        Destino: {{ $venta->movimiento == 'CAJA' ? 'Caja' : 'Banco' }}
                                    </div>
                                </td>

                                <td class="px-2 py-1.5 align-middle text-right bg-slate-50 border-r border-slate-300">
                                    <div class="text-[9px] text-slate-500 font-bold uppercase mb-0.5">IGV: {{ $venta->con_igv ? 'SÍ' : 'NO' }}</div>
                                    <div class="text-sm font-black text-slate-900">S/ {{ number_format($venta->total_facturado, 2) }}</div>
                                </td>
                                
                                <td class="px-2 py-1.5 align-middle text-center bg-slate-50">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('ventas.edit', $venta->id) }}" class="text-indigo-600 hover:text-indigo-900 transition" title="Editar Venta">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        <button type="button" @click="openModal('{{ route('ventas.destroy', $venta->id) }}', true)" class="text-slate-400 hover:text-red-600 transition" title="Anular Venta">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-slate-500 font-bold">Sin ventas registradas en este periodo.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($ventas->count() > 0)
                        <tfoot class="bg-slate-300 text-slate-900 border-t-2 border-slate-500">
                            <tr>
                                <td colspan="7" class="px-2 py-2 text-right text-[10px] font-black uppercase tracking-widest border-r border-slate-400">Total Periodo:</td>
                                <td class="px-2 py-2 text-right text-sm font-black border-r border-slate-400">S/ {{ number_format($ventas->sum('total_facturado'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="pt-2">
                <div class="flex flex-col md:flex-row justify-between items-end mb-1 gap-2">
                    <div class="flex items-center gap-1.5 mb-2 xl:mb-0">
                        <div class="w-1.5 h-3 bg-emerald-700"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest leading-none">Ventas Externas</h3>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <form action="{{ route('ventas.index') }}" method="GET" class="flex border border-slate-400 rounded-sm overflow-hidden bg-white shadow-sm items-center">
                            <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                            <input type="hidden" name="fecha_fin" value="{{ $fecha_fin }}">
                            <input type="hidden" name="movimiento" value="{{ $movimiento }}">
                            
                            <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1.5 flex items-center border-r border-slate-300 uppercase">Desde</span>
                            <input type="date" name="fecha_inicio_fuera" value="{{ $fecha_inicio_fuera }}" class="text-[10px] border-0 py-1 px-2 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50">
                            
                            <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1.5 flex items-center border-l border-r border-slate-300 uppercase">Hasta</span>
                            <input type="date" name="fecha_fin_fuera" value="{{ $fecha_fin_fuera }}" class="text-[10px] border-0 py-1 px-2 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50">

                            <select name="movimiento" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase">
                                <option value="todos" {{ $movimiento == 'todos' ? 'selected' : '' }}>Todos (Destino)</option>
                                <option value="CAJA" {{ $movimiento == 'CAJA' ? 'selected' : '' }}>Caja Físico</option>
                                <option value="DEPOSITO" {{ $movimiento == 'DEPOSITO' ? 'selected' : '' }}>Depósito</option>
                            </select>

                            <select name="vendedor" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase">
                                <option value="todos" {{ $vendedor == 'todos' ? 'selected' : '' }}>Todos (Vend.)</option>
                                <option value="Fredy C." {{ $vendedor == 'Fredy C.' ? 'selected' : '' }}>Fredy C.</option>
                                <option value="Of. Huasqui" {{ $vendedor == 'Of. Huasqui' ? 'selected' : '' }}>Of. Huasqui</option>
                                <option value="Daniel B." {{ $vendedor == 'Daniel B.' ? 'selected' : '' }}>Daniel B.</option>
                                <option value="Procesos/Licitacio" {{ $vendedor == 'Procesos/Licitacio' ? 'selected' : '' }}>Procesos/Licitacio</option>
                                <option value="Ing. Andi" {{ $vendedor == 'Ing. Andi' ? 'selected' : '' }}>Ing. Andi</option>
                                <option value="Sr. Ricardo" {{ $vendedor == 'Sr. Ricardo' ? 'selected' : '' }}>Sr. Ricardo</option>
                                <option value="Planta" {{ $vendedor == 'Planta' ? 'selected' : '' }}>Planta</option>
                                <option value="Traslado" {{ $vendedor == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Ing. Elias" {{ $vendedor == 'Ing. Elias' ? 'selected' : '' }}>Ing. Elias</option>
                            </select>

                            <select name="producto" class="text-[10px] border-0 border-l border-slate-300 py-1 pl-2 pr-6 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 uppercase max-w-[130px]">
                                <option value="todos" {{ $producto_filtro == 'todos' ? 'selected' : '' }}>Todos (Prod.)</option>
                                <option value="Agrocal - Mix" {{ $producto_filtro == 'Agrocal - Mix' ? 'selected' : '' }}>Agrocal - Mix</option>
                                <option value="Cal Bordalesa" {{ $producto_filtro == 'Cal Bordalesa' ? 'selected' : '' }}>Cal Bordalesa</option>
                                <option value="Hidrocal" {{ $producto_filtro == 'Hidrocal' ? 'selected' : '' }}>Hidrocal</option>
                                <option value="Roca Fosf.con marca" {{ $producto_filtro == 'Roca Fosf.con marca' ? 'selected' : '' }}>Roca Fosf.con marca</option>
                                <option value="Sulfato de Calcio" {{ $producto_filtro == 'Sulfato de Calcio' ? 'selected' : '' }}>Sulfato de Calcio</option>
                                <option value="Ulex-30" {{ $producto_filtro == 'Ulex-30' ? 'selected' : '' }}>Ulex-30</option>
                                <option value="Super Magnocal - Mix" {{ $producto_filtro == 'Super Magnocal - Mix' ? 'selected' : '' }}>Super Magnocal - Mix</option>
                                <option value="Nutri Abonaza" {{ $producto_filtro == 'Nutri Abonaza' ? 'selected' : '' }}>Nutri Abonaza</option>
                                <option value="Agro Yeso" {{ $producto_filtro == 'Agro Yeso' ? 'selected' : '' }}>Agro Yeso</option>
                                <option value="Dolomita" {{ $producto_filtro == 'Dolomita' ? 'selected' : '' }}>Dolomita</option>
                                <option value="Abonaza Papero" {{ $producto_filtro == 'Abonaza Papero' ? 'selected' : '' }}>Abonaza Papero</option>
                                <option value="Humus de Lom." {{ $producto_filtro == 'Humus de Lom.' ? 'selected' : '' }}>Humus de Lom.</option>
                                <option value="Ulexita sin Marca" {{ $producto_filtro == 'Ulexita sin Marca' ? 'selected' : '' }}>Ulexita sin Marca</option>
                                <option value="Gallinaza" {{ $producto_filtro == 'Gallinaza' ? 'selected' : '' }}>Gallinaza</option>
                                <option value="Cal Nieve" {{ $producto_filtro == 'Cal Nieve' ? 'selected' : '' }}>Cal Nieve</option>
                                <option value="Agrocal - Mix Tropical" {{ $producto_filtro == 'Agrocal - Mix Tropical' ? 'selected' : '' }}>Agrocal - Mix Tropical</option>
                                <option value="Bio Cal" {{ $producto_filtro == 'Bio Cal' ? 'selected' : '' }}>Bio Cal</option>
                                <option value="Yeso S/M" {{ $producto_filtro == 'Yeso S/M' ? 'selected' : '' }}>Yeso S/M</option>
                                <option value="Carbon Mineral S/M" {{ $producto_filtro == 'Carbon Mineral S/M' ? 'selected' : '' }}>Carbon Mineral S/M</option>
                                <option value="Filler 40 kg. S/M" {{ $producto_filtro == 'Filler 40 kg. S/M' ? 'selected' : '' }}>Filler 40 kg. S/M</option>
                                <option value="Kal Hidratada-Hidro" {{ $producto_filtro == 'Kal Hidratada-Hidro' ? 'selected' : '' }}>Kal Hidratada-Hidro</option>
                                <option value="Agrocal mix sulfocalcica" {{ $producto_filtro == 'Agrocal mix sulfocalcica' ? 'selected' : '' }}>Agrocal mix sulfocalcica</option>
                                <option value="Carbocal 50kg" {{ $producto_filtro == 'Carbocal 50kg' ? 'selected' : '' }}>Carbocal 50kg</option>
                            </select>

                            <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-3 py-1.5 transition border-l border-slate-400 text-[10px] font-bold uppercase">Filtrar</button>
                        </form>
                        <a href="{{ route('ventas_fuera.create') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-3 py-1.5 rounded-sm text-[10px] font-bold uppercase transition shadow-sm">Registrar Venta Ext.</a>
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar border border-slate-400 bg-white shadow-sm rounded-sm">
                    <table class="w-full text-left text-[11px] whitespace-nowrap">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider border-b-2 border-slate-600">
                            <tr>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-20">Fecha</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-32">Documento</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 min-w-[180px]">Cliente / Dirección</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 w-32">Vendedor / Zona</th>
                                <th class="px-2 py-1.5 border-r border-slate-700">Detalle Mercadería</th>
                                <th class="px-2 py-1.5 border-r border-slate-700 text-right w-32 bg-slate-800">Total</th>
                                <th class="px-2 py-1.5 text-center w-20 bg-slate-800">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-300 text-slate-800">
                            @forelse($ventasFuera as $vf)
                            <tr class="hover:bg-slate-100 transition-colors">
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 font-bold">
                                    {{ $vf->fecha->format('d/m/y') }}
                                </td>
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 whitespace-normal">
                                    <div class="font-black uppercase">{{ $vf->numero_comprobante }}</div>
                                    <div class="text-[9px] text-slate-500 uppercase">{{ $vf->tipo_comprobante }}</div>
                                    @if($vf->codigo_guia || $vf->numero_guia)
                                        <div class="text-[9px] text-indigo-700 font-bold mt-0.5">Guía: {{ $vf->codigo_guia }} {{ $vf->numero_guia }}</div>
                                    @endif
                                </td>
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 whitespace-normal">
                                    <div class="font-bold leading-tight">{{ $vf->cliente }}</div>
                                    <div class="text-[9px] text-slate-600 uppercase mt-0.5">{{ $vf->lugar ?: 'Sin Ubicación' }}</div>
                                    @if($vf->detalle)
                                        <div class="text-[9px] text-slate-600 mt-0.5 italic">Nota: {{ $vf->detalle }}</div>
                                    @endif
                                </td>
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 whitespace-normal">
                                    <div class="font-bold">{{ $vf->vendedor }}</div>
                                    <div class="text-[9px] text-slate-600 uppercase">{{ $vf->zona }}</div>
                                </td>
                                <td class="px-2 py-1.5 align-top border-r border-slate-300 p-0">
                                    <div class="w-full">
                                        <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center py-0.5 px-1">
                                            <span class="font-bold uppercase text-[10px] truncate" title="{{ $vf->producto }}">
                                                - {{ $vf->producto ?: 'Sin Nombre' }}
                                            </span>
                                            <div class="flex items-center gap-2 text-[9px] ml-1 xl:ml-0 whitespace-nowrap">
                                                <span>Cant: <span class="font-bold text-slate-900">{{ number_format($vf->cantidad, 2) }}</span></span>
                                                <span class="text-slate-300">|</span>
                                                <span>Ton: <span class="font-bold text-indigo-700">{{ number_format($vf->tonelada ?? 0, 2) }}</span></span>
                                                <span class="text-slate-300">|</span>
                                                <span>P.U: <span class="font-bold text-emerald-700">S/{{ number_format($vf->precio_unitario, 2) }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1.5 align-middle text-right bg-slate-50 border-r border-slate-300">
                                    <div class="text-[9px] text-slate-500 font-bold uppercase mb-0.5">IGV: {{ $vf->con_igv ? 'SÍ' : 'NO' }}</div>
                                    <div class="text-sm font-black text-slate-900">S/ {{ number_format($vf->total_facturado, 2) }}</div>
                                </td>
                                <td class="px-2 py-1.5 align-middle text-center bg-slate-50">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('ventas_fuera.edit', $vf->id) }}" class="text-indigo-600 hover:text-indigo-900 transition p-1" title="Editar Venta Externa">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        <button type="button" @click="openModal('{{ route('ventas_fuera.destroy', $vf->id) }}', false)" class="text-slate-400 hover:text-red-600 transition p-1" title="Eliminar Venta Externa">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-2 py-4 text-center text-slate-500 font-bold">Sin ventas externas registradas en este periodo.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-300 text-slate-900 border-t-2 border-slate-500">
                            <tr>
                                <td colspan="5" class="px-2 py-2 text-right text-[10px] uppercase font-black border-r border-slate-400">Total Externas:</td>
                                <td class="px-2 py-2 text-right text-sm font-black text-emerald-700 border-r border-slate-400">S/ {{ number_format($totalFuera, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-300">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                    <div class="w-1.5 h-3 bg-slate-800"></div> Análisis Consolidado por Agente
                </h3>

                @if(isset($consolidadoVendedores) && $consolidadoVendedores->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @foreach($consolidadoVendedores as $vendedor => $productos)
                            <div class="border border-slate-400 bg-white shadow-md overflow-hidden rounded-sm">
                                <div class="px-2.5 py-1.5 bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest border-b-2 border-slate-600 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Agente: <span class="text-indigo-300">{{ $vendedor }}</span>
                                </div>
                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-[10px] text-left whitespace-nowrap">
                                        <thead class="bg-slate-800 text-white uppercase font-bold border-b border-slate-500 text-[9px]">
                                            <tr>
                                                <th class="px-2 py-1 border-r border-slate-600">Mineral</th>
                                                <th class="px-2 py-1 border-r border-slate-600 text-center">Cant.</th>
                                                <th class="px-2 py-1 border-r border-slate-600 text-center">Ton.</th>
                                                <th class="px-2 py-1 text-right">Facturado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 text-slate-800 bg-white">
                                            @php $sumaTotalVendedor = 0; @endphp
                                            @foreach($productos as $nombreProducto => $datos)
                                                @php $sumaTotalVendedor += $datos['total_facturado']; @endphp
                                                <tr class="hover:bg-slate-100 transition-colors align-top">
                                                    <td class="px-2 py-1.5 border-r border-slate-200 font-bold truncate max-w-[120px]" title="{{ $nombreProducto }}">{{ $nombreProducto }}</td>
                                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold">{{ number_format($datos['cantidad'], 1) }}</td>
                                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-black text-indigo-700 bg-indigo-50/30">{{ number_format($datos['tonelada'], 1) }}</td>
                                                    <td class="px-2 py-1.5 text-right font-black text-slate-900 bg-slate-50">S/{{ number_format($datos['total_facturado'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-slate-700 text-white border-t-2 border-slate-500">
                                            <tr>
                                                <td colspan="3" class="px-2 py-1.5 text-right uppercase font-black text-[9px] border-r border-slate-500">Rendimiento:</td>
                                                <td class="px-2 py-1.5 text-right text-xs font-black bg-slate-800">S/{{ number_format($sumaTotalVendedor, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-slate-50 border border-slate-300 py-4 text-center text-slate-500 text-[11px] font-bold">
                        Sin datos para consolidar en este periodo.
                    </div>
                @endif
            </div>

        </div>

        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center border border-slate-200">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border-[4px] border-red-50 bg-red-100 mb-4 shadow-sm">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight" x-text="message"></h3>
                <p class="text-sm text-slate-500 mb-6 font-medium leading-snug" x-text="subMessage"></p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="showModal = false" class="w-full px-4 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition">
                        Cancelar
                    </button>
                    <form :action="actionUrl" method="POST" class="w-full m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-600/30">
                            Sí, Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('confirmModal', () => ({
                showModal: false,
                actionUrl: '',
                message: '',
                subMessage: '',
                
                openModal(url, isInternal) {
                    this.actionUrl = url;
                    
                    if (isInternal) {
                        this.message = '¿Anular Venta?';
                        this.subMessage = 'Esta acción anulará el registro y devolverá los sacos automáticamente al almacén de origen.';
                    } else {
                        this.message = '¿Eliminar Venta Externa?';
                        this.subMessage = 'Esta acción eliminará permanentemente la venta del sistema. ¿Deseas continuar?';
                    }
                    
                    this.showModal = true;
                }
            }));
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-top: 1px solid #e2e8f0; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 0px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>



