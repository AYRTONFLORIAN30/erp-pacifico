<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight flex items-center gap-2 tracking-tight">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Dashboard Gerencial
            </h2>
            
            <div class="flex items-center">
                <form action="{{ route('dashboard') }}" method="GET" class="flex border border-slate-300 rounded-lg overflow-hidden bg-white shadow-sm">
                    <select name="mes" class="text-sm border-0 py-2 pl-3 pr-8 focus:ring-0 capitalize text-slate-700 font-bold cursor-pointer hover:bg-slate-50">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('es')->isoFormat('MMMM') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="anio" class="text-sm border-0 border-l border-slate-200 py-2 pl-3 pr-8 focus:ring-0 text-slate-700 font-bold cursor-pointer hover:bg-slate-50">
                        @foreach(range(2024, 2026) as $y)
                            <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[98%] mx-auto space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 relative overflow-hidden transition hover:shadow-md">
                    <div class="absolute right-0 top-0 mt-4 mr-4 bg-emerald-100 text-emerald-600 p-2 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Ingresos del Mes</p>
                        <h3 class="text-3xl font-black text-slate-800">S/ {{ number_format($totalVentasSoles, 2) }}</h3>
                        <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            Total Facturado General
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 relative overflow-hidden transition hover:shadow-md">
                    <div class="absolute right-0 top-0 mt-4 mr-4 bg-blue-100 text-blue-600 p-2 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Volumen de Ventas</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $conteoVentas }} <span class="text-sm font-medium text-slate-500 lowercase">operaciones</span></h3>
                        <p class="text-xs text-blue-600 font-bold mt-2 flex items-center gap-1">
                            Tickets / Facturas Emitidas
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-yellow-200 p-6 relative overflow-hidden transition hover:shadow-md">
                    <div class="absolute right-0 top-0 mt-4 mr-4 bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Retenciones SPOT</p>
                        <h3 class="text-3xl font-black text-slate-800">S/ {{ number_format($montoDetracciones, 2) }}</h3>
                        <p class="text-xs text-yellow-600 font-bold mt-2 flex items-center gap-1">
                            Total detracciones del periodo
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                        <h3 class="font-black text-slate-700 uppercase tracking-wide">Top Clientes: ¿Quién compra más?</h3>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold">SOLES (S/)</span>
                    </div>
                    
                    <div id="chart-clientes" class="w-full flex-grow" style="min-height: 250px;"></div>

                    <div id="contenedor-btn-clientes" class="w-full text-center mt-2 hidden">
                        <button id="btn-mostrar-todos" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 py-2 px-4 rounded-full transition duration-300">
                            Ver todos los clientes ⬇️
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                        <h3 class="font-black text-slate-700 uppercase tracking-wide">Ventas por Zona</h3>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-bold">SOLES (S/)</span>
                    </div>
                    <div id="chart-zonas" class="w-full h-[320px] flex justify-center mt-4"></div>
                </div>

            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                    <h3 class="font-black text-slate-700 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Evolución de Ventas Diarias
                    </h3>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold">SOLES (S/)</span>
                </div>
                <div id="chart-ventas-diarias" class="w-full h-[350px]"></div>
            </div>

        </div>
    </div>

    <style>
        .tooltip-top-clientes {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
        .tooltip-top-clientes .apexcharts-tooltip-title { 
            display: none !important; 
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // DATOS DESDE PHP (Revisa tu DashboardController.php para asegurarte de que no tienen Str::limit)
            const clientesNombresTotal = @json($clientesNombres) || [];
            const clientesMontosTotal = @json($clientesMontos) || [];
            
            const zonasNombres = @json($zonasNombres) || [];
            const zonasTotales = @json($zonasMontos) || [];
            
            const diasLabels = @json($diasLabels) || [];
            const diasMontos = @json($diasMontos) || [];

            // =========================================================
            // 1. LÓGICA GRÁFICA TOP CLIENTES
            // =========================================================
            let chartClientesInstance = null;
            let mostrandoTodos = false;

            if(clientesNombresTotal.length > 0) {
                let top5Nombres = [...clientesNombresTotal].slice(0, 5);
                let top5Montos = [...clientesMontosTotal].slice(0, 5);

                if(clientesNombresTotal.length > 5) {
                    document.getElementById('contenedor-btn-clientes').classList.remove('hidden');
                }

                var maxValue = Math.max(...clientesMontosTotal); 

                var baseOptionsClientes = {
                    chart: { type: 'bar', toolbar: { show: false }, fontFamily: 'inherit',
                        animations: { enabled: true, easing: 'easeinout', speed: 800 } 
                    },
                    plotOptions: { 
                        bar: { 
                            borderRadius: 4, 
                            horizontal: true,
                            dataLabels: { position: 'top' },
                            barHeight: '60%'
                        } 
                    }, 
                    colors: ['#4f46e5'], 
                    dataLabels: {
                        enabled: true, 
                        textAnchor: 'start', 
                        style: { colors: ['#475569'], fontSize: '11px', fontWeight: 700 },
                        offsetX: 6, 
                        formatter: function (val) { return "S/ " + val.toLocaleString(); },
                        dropShadow: { enabled: false } 
                    },
                    yaxis: { 
                        labels: { 
                            maxWidth: 350, 
                            style: { fontWeight: 700, colors: '#475569', fontSize: '10px' }
                        } 
                    },
                    xaxis: {
                        labels: { show: false },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        max: maxValue * 1.20
                    },
                    grid: { show: false, padding: { left: 10, right: 10 } },
                    legend: { show: false },
                    
                    // APLICAMOS LA CLASE AISLADA PARA QUE NO DAÑE OTROS GRÁFICOS
                    tooltip: { 
                        cssClass: 'tooltip-top-clientes',
                        custom: function({series, seriesIndex, dataPointIndex, w}) {
                            var nombreExacto = mostrandoTodos ? clientesNombresTotal[dataPointIndex] : top5Nombres[dataPointIndex];
                            var montoExacto = series[seriesIndex][dataPointIndex];
                            
                            return '<div style="padding: 12px; background: #1e293b; color: white; border-radius: 8px; border: 1px solid #334155; display: flex; flex-direction: column; gap: 4px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.4); max-width: 350px;">' +
                                   '<div style="font-size: 11px; font-weight: 800; color: #a5b4fc; text-transform: uppercase; white-space: normal; word-wrap: break-word; line-height: 1.3;">' + nombreExacto + '</div>' +
                                   '<div style="font-size: 16px; font-weight: 900; color: white;">S/ ' + montoExacto.toLocaleString() + '</div>' +
                                   '</div>';
                        }
                    }
                };

                var currentOptions = { ...baseOptionsClientes,
                    series: [{ name: 'Ventas', data: top5Montos }],
                    chart: { ...baseOptionsClientes.chart, height: Math.max(250, top5Nombres.length * 45) },
                    xaxis: { categories: top5Nombres, labels: { show: false }, max: maxValue * 1.20 }
                };

                chartClientesInstance = new ApexCharts(document.querySelector("#chart-clientes"), currentOptions);
                chartClientesInstance.render();

                document.getElementById('btn-mostrar-todos').addEventListener('click', function() {
                    const btn = this;
                    
                    if(!mostrandoTodos) {
                        chartClientesInstance.updateOptions({
                            chart: { height: Math.max(300, clientesNombresTotal.length * 45) },
                            xaxis: { categories: clientesNombresTotal, max: maxValue * 1.20 }
                        });
                        chartClientesInstance.updateSeries([{ data: clientesMontosTotal }]);
                        btn.innerHTML = 'Ocultar clientes ⬆️';
                        mostrandoTodos = true;
                    } else {
                        chartClientesInstance.updateOptions({
                            chart: { height: Math.max(250, top5Nombres.length * 45) },
                            xaxis: { categories: top5Nombres, max: maxValue * 1.20 }
                        });
                        chartClientesInstance.updateSeries([{ data: top5Montos }]);
                        btn.innerHTML = 'Ver todos los clientes ⬇️';
                        mostrandoTodos = false;
                    }
                });

            } else {
                document.querySelector("#chart-clientes").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 font-bold min-h-[250px]">No hay clientes en este periodo.</div>';
            }


            // =========================================================
            // 2. DONUT (ZONAS)
            // =========================================================
            if(zonasNombres.length > 0) {
                var optionsZonas = {
                    series: zonasTotales, labels: zonasNombres,
                    chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
                    colors: ['#059669', '#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#d1fae5'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true, name: { show: true, fontWeight: 700, color: '#64748b' },
                                    value: { show: true, fontWeight: 900, color: '#0f172a', formatter: function (val) { return "S/ " + val.toLocaleString(); } },
                                    total: {
                                        show: true, showAlways: true, label: 'Total General', color: '#64748b',
                                        formatter: function (w) { return "S/ " + w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0).toLocaleString(); }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    legend: { position: 'bottom', fontWeight: 600, markers: { radius: 12 } },
                    stroke: { show: true, colors: '#ffffff', width: 2 },
                    tooltip: { y: { formatter: function(val) { return "S/ " + val.toLocaleString(); } } }
                };
                new ApexCharts(document.querySelector("#chart-zonas"), optionsZonas).render();
            } else {
                 document.querySelector("#chart-zonas").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 font-bold">No hay zonas en este periodo.</div>';
            }

            // =========================================================
            // 3. BARRAS VERTICALES (EVOLUCIÓN DIARIA - AHORA LIMPIO)
            // =========================================================
            var optionsVentasDiarias = {
                series: [{ name: 'Total Facturado (S/)', data: diasMontos }],
                chart: { type: 'bar', height: 350, toolbar: { show: false }, fontFamily: 'inherit' },
                plotOptions: {
                    bar: {
                        borderRadius: 3,
                        columnWidth: '60%',
                        dataLabels: { position: 'top' }
                    }
                },
                colors: ['#4f46e5'], 
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val > 0 ? "S/" + val.toLocaleString() : ""; },
                    offsetY: -20,
                    style: { fontSize: '9px', colors: ["#64748b"] }
                },
                xaxis: {
                    categories: diasLabels,
                    title: { text: 'Días del Mes', style: { fontWeight: 700, color: '#94a3b8' } },
                    labels: { style: { fontWeight: 600, colors: '#64748b' } },
                    axisBorder: { show: true, color: '#e2e8f0' }
                },
                yaxis: {
                    title: { text: 'Monto (Soles)', style: { fontWeight: 700, color: '#94a3b8' } },
                    labels: {
                        formatter: function (value) { return "S/ " + value.toLocaleString(); },
                        style: { fontWeight: 600, colors: '#64748b' }
                    }
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                
                // Tooltip limpio por defecto, ya no se cruzará el estilo
                tooltip: {
                    theme: 'light',
                    y: { formatter: function (val) { return "S/ " + val.toLocaleString(); } }
                }
            };
            new ApexCharts(document.querySelector("#chart-ventas-diarias"), optionsVentasDiarias).render();

        });
    </script>
</x-app-layout>
  
