<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    💰 Gestión Contable de Egresos
                </h2>
                <p class="text-sm text-slate-500 mt-1">Control financiero detallado</p>
            </div>
            <div class="hidden md:block text-right">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Periodo Filtrado</span>
                <span class="text-sm font-extrabold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-md border border-emerald-200 shadow-sm">
                    {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="egresosModal()">
        <div class="max-w-[98%] mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg relative font-bold shadow-sm" role="alert">
                    <span class="block sm:inline">✓ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative font-bold shadow-sm" role="alert">
                    <span class="block sm:inline">🚨 {{ session('error') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative font-bold shadow-sm" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col xl:flex-row gap-4 items-center justify-between">
                
                <form action="{{ route('egresos.index') }}" method="GET" class="flex flex-col md:flex-row border border-slate-300 rounded-lg overflow-hidden bg-white shadow-sm items-center w-full xl:w-auto">
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-2.5 flex items-center border-b md:border-b-0 md:border-r border-slate-300 uppercase w-full md:w-auto justify-center">Desde</span>
                    <input type="date" name="fecha_inicio" value="{{ $fecha_inicio }}" class="text-sm border-0 py-2 px-3 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50 w-full md:w-auto text-center">
                    
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-2.5 flex items-center border-y md:border-y-0 md:border-l md:border-r border-slate-300 uppercase w-full md:w-auto justify-center">Hasta</span>
                    <input type="date" name="fecha_fin" value="{{ $fecha_fin }}" class="text-sm border-0 py-2 px-3 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-50 w-full md:w-auto text-center">

                    <select name="metodo" class="text-xs border-t md:border-t-0 md:border-l border-slate-300 py-2.5 pl-3 pr-8 focus:ring-0 text-slate-800 font-bold cursor-pointer hover:bg-slate-100 w-full md:w-auto">
                        <option value="todos" {{ $metodo == 'todos' ? 'selected' : '' }}>💳 Todos los pagos</option>
                        <option value="Efectivo" {{ $metodo == 'Efectivo' ? 'selected' : '' }}>💵 Solo Efectivo</option>
                        <option value="Digital" {{ $metodo == 'Digital' ? 'selected' : '' }}>📱 Solo Digital</option>
                    </select>

                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2.5 text-xs font-bold uppercase transition-colors w-full md:w-auto h-full">Filtrar</button>
                </form>

                <div class="flex flex-wrap items-center w-full xl:w-auto border-t xl:border-t-0 xl:border-l border-slate-200 pt-4 xl:pt-0 xl:pl-4 justify-center">
                    <a href="{{ route('egresos.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md flex items-center gap-2 transition-transform hover:-translate-y-0.5 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nuevo Gasto
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-slate-200">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead class="bg-slate-800 text-slate-200 uppercase tracking-wider font-bold">
                            <tr>
                                <th class="px-3 py-4 border-r border-slate-600">Fecha</th>
                                <th class="px-3 py-4">RUC</th>
                                <th class="px-3 py-4 border-r border-slate-600 min-w-[150px]">Razón Social</th>
                                <th class="px-3 py-4 min-w-[120px]">Descripción</th>
                                <th class="px-3 py-4 border-r border-slate-600 min-w-[180px] whitespace-normal">Glosa</th>
                                <th class="px-3 py-4 text-center">Comp.</th>
                                <th class="px-3 py-4 text-center">Guía</th>
                                <th class="px-3 py-4 text-center border-r border-slate-600">NC/ND</th>
                                <th class="px-3 py-4 text-right bg-slate-900/50">Base Imp.</th>
                                <th class="px-3 py-4 text-right bg-slate-900/50">IGV</th>
                                <th class="px-3 py-4 text-right bg-slate-900/50 text-slate-400">No Grav.</th>
                                <th class="px-3 py-4 text-right bg-slate-700 text-white font-extrabold border-r border-slate-600 shadow-inner min-w-[100px]">TOTAL (S/)</th>
                                <th class="px-3 py-4 text-right">Detrac.</th>
                                <th class="px-3 py-4 text-center border-r border-slate-600">F. Detrac.</th>
                                <th class="px-3 py-4 text-center min-w-[90px]">Pago</th>
                                <th class="px-3 py-4 text-center">Estado</th>
                                <th class="px-3 py-4 text-right font-bold text-yellow-500">Saldo</th>
                                <th class="px-3 py-4 text-center border-r border-slate-600">Resp.</th>
                                <th class="px-3 py-4 text-center bg-slate-900">Acción</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($egresos as $egreso)
                                @php
                                    // 💡 REGLAS DE DETECCIÓN DE DATOS FALTANTES
                                    $faltaRuc = empty(trim($egreso->ruc));
                                    $faltaRazonSocial = empty(trim($egreso->razon_social));
                                    $faltaGlosa = empty(trim($egreso->glosa));
                                    $faltaComp = empty(trim($egreso->n_comprobante));
                                    $faltaGuia = empty(trim($egreso->guia));
                                    $faltaNCND = empty(trim($egreso->nc_nd));
                                    
                                    // Si CUALQUIERA de estos está vacío, la fila entera se marca de amarillo
                                    $estaIncompleto = $faltaRuc || $faltaRazonSocial || $faltaGlosa || $faltaComp || $faltaGuia || $faltaNCND;
                                @endphp

                                <tr class="transition-colors duration-150 group align-top {{ $estaIncompleto ? 'bg-yellow-200 hover:bg-yellow-300' : 'bg-white hover:bg-slate-50' }}">
                                    
                                    <td class="px-3 py-3 border-r {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }} font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($egreso->fecha_emision)->format('d/m/Y') }}
                                    </td>
                                    
                                    <td class="px-3 py-3 font-mono {{ $faltaRuc ? 'text-red-700 font-bold' : 'text-slate-500' }}">
                                        @if($faltaRuc)
                                            ⚠️ Falta RUC
                                        @else
                                            {{ $egreso->ruc }}
                                        @endif
                                    </td>
                                    
                                    <td class="px-3 py-3 border-r {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }} font-bold leading-tight whitespace-normal {{ $faltaRazonSocial ? 'text-red-700' : 'text-slate-800' }}">
                                        @if($faltaRazonSocial)
                                            ⚠️ Falta Razón Social
                                        @else
                                            {{ $egreso->razon_social }}
                                        @endif
                                    </td>
                                    
                                    <td class="px-3 py-3 text-slate-700 whitespace-normal">{{ $egreso->descripcion }}</td>
                                    
                                    <td class="px-3 py-3 border-r {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }} text-slate-500 italic text-[11px] leading-tight whitespace-normal">
                                        @if($faltaGlosa)
                                            <span class="text-red-700 font-bold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                ⚠️ Falta Glosa
                                            </span>
                                        @else
                                            {{ $egreso->glosa }}
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-center font-mono {{ $faltaComp ? 'text-red-700 font-bold' : 'text-slate-700' }}">
                                        @if($faltaComp)
                                            ⚠️ Falta Comp.
                                        @else
                                            {{ $egreso->n_comprobante }}
                                        @endif
                                    </td>
                                    
                                    <td class="px-3 py-3 text-center text-[10px] {{ $faltaGuia ? 'text-red-700 font-bold' : '' }}">
                                        @if($faltaGuia)
                                            ⚠️ Falta Guía
                                        @else
                                            <div class="text-slate-800 font-bold">{{ $egreso->guia }}</div>
                                            <div class="text-slate-400">({{ $egreso->estado_guia }})</div>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-center border-r {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }} {{ $faltaNCND ? 'text-red-700 font-bold' : 'text-slate-600' }}">
                                        @if($faltaNCND)
                                            ⚠️ Falta NC/ND
                                        @else
                                            {{ $egreso->nc_nd }}
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-right {{ $estaIncompleto ? 'bg-yellow-300/50' : 'bg-slate-50/50' }} text-slate-600 font-bold">
                                        {{ number_format($egreso->base_imponible, 2) }}
                                    </td>
                                    <td class="px-3 py-3 text-right {{ $estaIncompleto ? 'bg-yellow-300/50' : 'bg-slate-50/50' }} text-slate-600 font-bold">
                                        {{ number_format($egreso->igv, 2) }}
                                    </td>
                                    <td class="px-3 py-3 text-right {{ $estaIncompleto ? 'bg-yellow-300/50' : 'bg-slate-50/50' }} text-slate-500 font-bold">
                                        {{ number_format($egreso->no_gravado, 2) }}
                                    </td>

                                    <td class="px-3 py-3 text-right {{ $estaIncompleto ? 'bg-yellow-100 border-yellow-400' : 'bg-slate-100 border-slate-200' }} border-l border-r">
                                        <div class="font-mono font-bold text-slate-900 text-sm">
                                            S/ {{ number_format($egreso->total, 2) }}
                                        </div>
                                        @if($egreso->otras_tasas > 1)
                                            <div class="flex flex-col items-end mt-1">
                                                <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-1.5 rounded" title="Pago en Dólares">
                                                    $ {{ number_format($egreso->total / $egreso->otras_tasas, 2) }}
                                                </span>
                                                <span class="text-[9px] text-slate-500 mt-0.5">T.C. {{ $egreso->otras_tasas }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-right text-red-600 font-medium">
                                        @if($egreso->detraccion_monto > 0)
                                            {{ number_format($egreso->detraccion_monto, 2) }}
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-center text-[11px] border-r {{ $estaIncompleto ? 'border-yellow-400 text-slate-600 font-bold' : 'border-slate-100 text-slate-500' }}">
                                        {{ $egreso->detraccion_fecha ? \Carbon\Carbon::parse($egreso->detraccion_fecha)->format('d/m/Y') : '-' }}
                                    </td>

                                    <td class="px-3 py-3 text-center text-[11px]">
                                        @if($egreso->metodo_pago == 'Efectivo')
                                            <span class="text-emerald-700 font-black whitespace-nowrap"><span class="text-sm">💵</span> Efectivo</span>
                                        @elseif($egreso->metodo_pago == 'Digital')
                                            <span class="text-blue-700 font-black whitespace-nowrap"><span class="text-sm">📱</span> Digital</span>
                                        @else
                                            <span class="text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        @if($egreso->estado_pago == 'CANCELADO')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">PAGADO</span>
                                        @elseif($egreso->estado_pago == 'PENDIENTE')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">DEUDA</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $egreso->estado_pago }}</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-3 text-right font-bold border-l {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }} {{ $egreso->saldo_pendiente > 0 ? 'text-red-600' : 'text-slate-500' }}">
                                        {{ number_format($egreso->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="px-3 py-3 text-center text-[10px] text-slate-600 font-bold border-r {{ $estaIncompleto ? 'border-yellow-400' : 'border-slate-100' }}">
                                        {{ strtok($egreso->responsable, " ") }}
                                    </td>

                                    <td class="px-2 py-3 text-center {{ $estaIncompleto ? 'bg-yellow-300/50' : 'bg-slate-50/50' }}">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('egresos.edit', $egreso->id) }}" class="text-indigo-700 hover:text-indigo-900 transition bg-white p-1.5 rounded-md shadow-sm border {{ $estaIncompleto ? 'border-yellow-500' : 'border-slate-200' }}" title="Editar Egreso">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                            <button type="button" @click="openModal('{{ route('egresos.destroy', $egreso->id) }}')" class="text-red-600 hover:text-red-800 transition bg-white p-1.5 rounded-md shadow-sm border {{ $estaIncompleto ? 'border-yellow-500' : 'border-slate-200' }}" title="Eliminar Egreso">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="bg-yellow-50 text-slate-800 font-bold border-t-2 border-slate-300 text-xs">
                                <td colspan="8" class="px-3 py-4 text-right uppercase tracking-wider">
                                    TOTALES DEL PERIODO:
                                </td>
                                <td class="px-3 py-4 text-right border-l border-slate-300">
                                    {{ number_format($egresos->sum('base_imponible'), 2) }}
                                </td>
                                <td class="px-3 py-4 text-right border-l border-slate-300">
                                    {{ number_format($egresos->sum('igv'), 2) }}
                                </td>
                                <td class="px-3 py-4 text-right border-l border-slate-300">
                                    {{ number_format($egresos->sum('no_gravado'), 2) }}
                                </td>
                                <td class="px-3 py-4 text-right bg-yellow-300 text-yellow-900 border-l border-r border-yellow-400 text-sm font-black shadow-inner">
                                    S/ {{ number_format($egresos->sum('total'), 2) }}
                                </td>
                                <td colspan="7" class="bg-slate-50 border-t border-slate-300"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @if($egresos->isEmpty())
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Sin registros</h3>
                        <p class="mt-1 text-sm text-slate-500 font-medium">No hay movimientos para este periodo y filtro.</p>
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
                
                <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">¿Eliminar Egreso?</h3>
                <p class="text-sm text-slate-500 mb-6 font-medium leading-snug">Esta acción eliminará el registro financiero permanentemente de la base de datos.</p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="showModal = false" class="w-full px-4 py-2.5 bg-slate-100 text-slate-800 font-bold rounded-xl hover:bg-slate-200 transition">
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
            Alpine.data('egresosModal', () => ({
                showModal: false,
                actionUrl: '',
                
                openModal(url) {
                    this.actionUrl = url;
                    this.showModal = true;
                }
            }));
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; border: 2px solid #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>