<x-app-layout>
    <div x-data="{ openModalProducto: false }" @abrir-modal.window="openModalProducto = true">
        
        <x-slot name="header">
            <div class="flex items-center justify-between border-b border-slate-300 pb-2">
                <h2 class="font-bold text-lg text-slate-800 leading-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Gestión de Almacén y Producción
                </h2>
                <button x-data @click="$dispatch('abrir-modal')" type="button" class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-1.5 rounded-sm text-[11px] font-bold uppercase transition">
                    + Nuevo Mineral
                </button>
            </div>
        </x-slot>

        <div class="py-4 bg-slate-50 min-h-screen">
            <div class="max-w-[98%] mx-auto space-y-6">

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-2 text-xs font-bold uppercase rounded-sm">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if (session('error') || $errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-2 text-xs font-bold uppercase rounded-sm">
                        🚨 {{ session('error') ?? 'Error en la operación' }}
                        @if($errors->any())
                            <ul class="list-disc pl-5 mt-1 text-[10px] font-medium normal-case">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white border border-slate-300 p-3 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Catálogo de Minerales</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $totalProductos }} <span class="text-[10px] text-slate-500 font-normal uppercase">ítems registrados</span></h3>
                        </div>
                        <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div class="bg-white border border-slate-300 p-3 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Estado de Existencias</p>
                            <h3 class="text-xl font-black {{ $alertasStock > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $alertasStock }} <span class="text-[10px] text-slate-500 font-normal uppercase">productos en nivel crítico global</span></h3>
                        </div>
                        <svg class="w-8 h-8 {{ $alertasStock > 0 ? 'text-red-200' : 'text-slate-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-2">
                        <div class="w-1.5 h-3 bg-emerald-600"></div> Registro de Producción Diaria
                    </h3>
                    <div class="bg-white border border-slate-300 p-3">
                        <form action="{{ route('inventario.store') }}" method="POST" class="flex flex-wrap md:flex-nowrap gap-3 items-end">
                            @csrf
                            <div class="w-full md:w-32">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Fecha Ingreso</label>
                                <input type="date" name="fecha" required value="{{ date('Y-m-d') }}" class="w-full text-xs border border-slate-300 bg-slate-50 focus:border-emerald-500 focus:ring-0 py-1.5 px-2">
                            </div>
                            
                            <div class="w-full md:flex-1 md:min-w-[200px]">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Mineral Procesado</label>
                                <select name="producto_id" required class="w-full text-[11px] border border-slate-300 bg-slate-50 focus:border-emerald-500 focus:ring-0 py-1.5 px-2 font-bold text-slate-800 cursor-pointer">
                                    <option value="">Seleccione el mineral producido...</option>
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-36">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Almacén Destino</label>
                                <select name="almacen_destino" required class="w-full text-xs font-bold border border-indigo-400 bg-indigo-50 text-indigo-900 focus:border-indigo-600 focus:ring-0 py-1.5 px-2 cursor-pointer">
                                    <option value="Tarma">📍 TARMA</option>
                                    <option value="Lima">📍 LIMA</option>
                                    <option value="Lambayeque">📍 LAMBAYEQUE</option>
                                </select>
                            </div>

                            <div class="w-full md:w-24">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Cant. Sacos</label>
                                <input type="number" step="0.01" name="cantidad" required min="0.01" class="w-full text-xs border border-emerald-400 bg-emerald-50 text-emerald-900 font-black focus:border-emerald-600 focus:ring-0 py-1.5 px-2 text-center" placeholder="0.00">
                            </div>

                            <div class="w-full md:w-40">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Motivo</label>
                                <select name="motivo" required class="w-full text-xs border border-slate-300 bg-slate-50 focus:border-emerald-500 focus:ring-0 py-1.5 px-2 cursor-pointer">
                                    <option value="Producción">Producción de Planta</option>
                                    <option value="Devolución">Devolución de Cliente</option>
                                    <option value="Ajuste">Ajuste / Traspaso</option>
                                </select>
                            </div>

                            <div class="w-full md:w-24">
                                <button type="submit" class="w-full py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold uppercase transition">
                                    + SUMAR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    
                    <div x-data="{ tab: 'tarma' }" class="xl:col-span-2 flex flex-col">
                        
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-2 gap-2">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <div class="w-1.5 h-3 bg-slate-800"></div> Catálogo de Existencias Físicas
                            </h3>
                            
                            <div class="flex bg-slate-200 p-1 rounded-sm shadow-inner">
                                <button @click="tab = 'tarma'" :class="tab === 'tarma' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1 text-[10px] font-black uppercase transition-all rounded-sm">TARMA</button>
                                <button @click="tab = 'lima'" :class="tab === 'lima' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1 text-[10px] font-black uppercase transition-all rounded-sm">LIMA</button>
                                <button @click="tab = 'lambayeque'" :class="tab === 'lambayeque' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1 text-[10px] font-black uppercase transition-all rounded-sm">LAMBAYEQUE</button>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-400 bg-white custom-scrollbar max-h-[500px]">
                            <table class="w-full text-left text-[11px] whitespace-nowrap">
                                <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider sticky top-0 border-b border-slate-600">
                                    <tr>
                                        <th class="px-3 py-2 border-r border-slate-600">Nombre del Mineral / Producto Identificado</th>
                                        <th class="px-3 py-2 border-r border-slate-600 text-center w-36">Stock Físico (Sacos)</th>
                                        <th class="px-3 py-2 text-center w-32">Nivel de Alerta</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-slate-800">
                                    @forelse($productos as $producto)
                                    <tr class="hover:bg-slate-100 transition-colors">
                                        <td class="px-3 py-2 font-bold text-[11px] whitespace-normal min-w-[300px] border-r border-slate-300">
                                            {{ $producto->nombre }}
                                        </td>
                                        <td class="px-3 py-2 text-center border-r border-slate-300">
                                            <span x-show="tab === 'tarma'" class="font-black text-sm {{ $producto->stock_tarma <= 0 ? 'text-red-700' : 'text-slate-900' }}">{{ number_format($producto->stock_tarma ?? 0, 2) }}</span>
                                            <span x-show="tab === 'lima'" style="display: none;" class="font-black text-sm {{ $producto->stock_lima <= 0 ? 'text-red-700' : 'text-slate-900' }}">{{ number_format($producto->stock_lima ?? 0, 2) }}</span>
                                            <span x-show="tab === 'lambayeque'" style="display: none;" class="font-black text-sm {{ $producto->stock_lambayeque <= 0 ? 'text-red-700' : 'text-slate-900' }}">{{ number_format($producto->stock_lambayeque ?? 0, 2) }}</span>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <div x-show="tab === 'tarma'">
                                                @if($producto->stock_tarma <= 0)
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 font-black text-[9px] uppercase border border-red-300">Agotado</span>
                                                @elseif($producto->stock_tarma <= 10)
                                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 font-black text-[9px] uppercase border border-orange-300">Crítico</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-black text-[9px] uppercase border border-emerald-300">Disponible</span>
                                                @endif
                                            </div>

                                            <div x-show="tab === 'lima'" style="display: none;">
                                                @if($producto->stock_lima <= 0)
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 font-black text-[9px] uppercase border border-red-300">Agotado</span>
                                                @elseif($producto->stock_lima <= 10)
                                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 font-black text-[9px] uppercase border border-orange-300">Crítico</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-black text-[9px] uppercase border border-emerald-300">Disponible</span>
                                                @endif
                                            </div>

                                            <div x-show="tab === 'lambayeque'" style="display: none;">
                                                @if($producto->stock_lambayeque <= 0)
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 font-black text-[9px] uppercase border border-red-300">Agotado</span>
                                                @elseif($producto->stock_lambayeque <= 10)
                                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 font-black text-[9px] uppercase border border-orange-300">Crítico</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-black text-[9px] uppercase border border-emerald-300">Disponible</span>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-slate-500 font-medium">El catálogo de productos está vacío. Registre un nuevo mineral.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="xl:col-span-1 flex flex-col mt-4 xl:mt-0">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-2">
                            <div class="w-1.5 h-3 bg-blue-600"></div> Historial de Producción
                        </h3>
                        <div class="overflow-y-auto border border-slate-400 bg-white custom-scrollbar flex-grow max-h-[500px]">
                            <table class="w-full text-[11px] text-left whitespace-nowrap">
                                <thead class="bg-slate-800 text-slate-100 uppercase font-bold sticky top-0 border-b border-slate-600">
                                    <tr>
                                        <th class="px-3 py-2 border-r border-slate-600">Detalle del Ingreso</th>
                                        <th class="px-3 py-2 text-right text-emerald-400 w-24">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-slate-800">
                                    @forelse($historial ?? [] as $item)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2 border-r border-slate-300 whitespace-normal">
                                            <div class="font-bold text-[11px] text-slate-900 leading-tight mb-0.5">{{ $item->producto->nombre ?? 'Producto Eliminado' }}</div>
                                            <div class="text-[9px] text-slate-500 font-bold uppercase">
                                                Fecha: {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }} | Ref: {{ $item->motivo }} <br>
                                                <span class="text-indigo-600">Destino: {{ $item->almacen_destino ?? 'Tarma' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-right font-black text-emerald-700 bg-emerald-50/50 text-sm">
                                            +{{ number_format($item->cantidad, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-8 text-center text-slate-500 font-medium">No existen movimientos de ingreso recientes.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div x-show="openModalProducto" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4" 
             style="display: none;">
            
            <div class="fixed inset-0 bg-slate-900 bg-opacity-80"></div>
            
            <div @click.away="openModalProducto = false" 
                 class="relative bg-white border border-slate-400 z-10 w-full max-w-sm">
                
                <div class="bg-slate-900 px-4 py-3 flex justify-between items-center">
                    <h3 class="font-black text-white uppercase text-xs tracking-widest">Creación de Mineral</h3>
                    <button @click="openModalProducto = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('inventario.producto.store') }}" method="POST" class="p-5 space-y-4 bg-slate-50">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nombre Técnico del Mineral / Producto</label>
                        <textarea name="nombre" required rows="3" class="w-full border-slate-300 focus:border-indigo-600 focus:ring-0 text-[11px] uppercase font-bold text-slate-800 p-2" placeholder="EJ. ROCA FOSFÓRICA BAYOVAR..."></textarea>
                        <p class="text-[9px] text-slate-500 mt-1 italic">Escriba el nombre tal cual aparecerá en las guías y facturas.</p>
                    </div>
                    
                    <input type="hidden" name="precio_base" value="0.00">
                    
                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit" class="w-full py-2 bg-indigo-700 hover:bg-indigo-800 text-white text-[11px] font-black uppercase tracking-wider transition border border-indigo-900">
                            Registrar en Catálogo
                        </button>
                        <button type="button" @click="openModalProducto = false" class="w-full py-2 border border-slate-300 bg-white text-slate-700 text-[10px] font-bold uppercase hover:bg-slate-100 transition text-center">
                            Descartar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-left: 1px solid #e2e8f0; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 0px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>