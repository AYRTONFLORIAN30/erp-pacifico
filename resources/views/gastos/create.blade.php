<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Nuevo Gasto Operativo
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('gastos.store') }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-200">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-700">Detalles del Gasto</h3>
                            <p class="text-sm text-slate-400">Registre los gastos menores de caja chica</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Fecha del Gasto</label>
                            <input type="date" name="fecha" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 focus:border-slate-800 focus:ring-slate-200 h-12">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Descripción / Detalle</label>
                            <input type="text" name="detalle" required placeholder="Ej: ALMUERZO PERSONAL" class="w-full rounded-xl border-slate-300 focus:border-slate-800 focus:ring-slate-200 h-12 uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Monto (S/)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold text-lg">S/</span>
                                <input type="number" step="0.01" name="monto" required placeholder="0.00" class="w-full pl-10 rounded-xl border-slate-300 focus:border-slate-800 focus:ring-slate-200 h-12 text-xl font-bold text-slate-700">
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-4">
                        <a href="{{ route('ventas.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-white transition">Cancelar</a>
                        <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-black text-white rounded-xl font-bold shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Guardar Gasto
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</x-app-layout>


