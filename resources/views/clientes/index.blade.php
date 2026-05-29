<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <svg class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Directorio de Clientes
            </h2>
            <div class="flex gap-3">
                <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold self-center uppercase">
                    Total: {{ $clientes->count() }} Registros
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[98%] mx-auto space-y-6">

            <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-700 uppercase">Importar Base de Datos (Excel)</h3>
                    <a href="#" class="text-[10px] font-bold text-indigo-600 hover:underline">DESCARGAR PLANTILLA SUGERIDA</a>
                </div>
                <form action="{{ route('clientes.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="flex flex-col md:flex-row items-end gap-4">
                        <div class="flex-grow w-full">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Seleccione el archivo .xlsx o .xls</label>
                            <input type="file" name="archivo_excel" accept=".xlsx, .xls" required 
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-dashed border-slate-300 rounded-xl p-2 cursor-pointer">
                        </div>
                        <button type="submit" class="w-full md:w-auto px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Procesar Clientes
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded shadow-sm font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded shadow-sm text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200">
                <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-700 uppercase">Cartera de Clientes Activos</h3>
                    <div class="relative w-64">
                        <input type="text" placeholder="Filtrar por RUC o Razón Social..." class="w-full pl-8 pr-3 py-1.5 rounded-lg border-slate-300 text-xs focus:ring-indigo-200">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full text-xs text-left border-collapse">
                        <thead class="bg-slate-800 text-slate-200 uppercase font-bold sticky top-0">
                            <tr>
                                <th class="px-4 py-3 border-r border-slate-700">RUC / DNI</th>
                                <th class="px-4 py-3 border-r border-slate-700">ID / Razón Social</th>
                                <th class="px-4 py-3 border-r border-slate-700 text-center">Lugar</th>
                                <th class="px-4 py-3 border-r border-slate-700">Contacto Directo</th>
                                <th class="px-4 py-3 text-center">Gestión</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($clientes as $cliente)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="px-4 py-3 font-medium text-slate-500 border-r border-slate-100 tracking-tighter">
                                    {{ $cliente->ruc ?? '---' }}
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-800 border-r border-slate-100 uppercase">
                                    {{ $cliente->razon_social }}
                                </td>
                                <td class="px-4 py-3 border-r border-slate-100 text-center">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full font-bold text-[9px] uppercase border border-slate-200">
                                        {{ $cliente->lugar ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-r border-slate-100">
                                    <div class="font-bold text-slate-700 uppercase">{{ $cliente->contacto ?? 'S/N' }}</div>
                                    <div class="text-[10px] text-indigo-500 font-semibold">{{ $cliente->telefono ?? '---' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button class="bg-white border border-slate-300 p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <p class="font-bold italic">No hay clientes registrados en la base de datos.</p>
                                        <p class="text-[10px]">Utilice el formulario de arriba para cargar su Excel de clientes.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</x-app-layout>

