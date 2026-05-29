<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="/" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-green-600" />
                        
                        <div class="hidden lg:block leading-tight">
                            <span class="block text-green-700 font-extrabold text-sm tracking-wide">ABONOS Y ENMIENDAS</span>
                            <span class="block text-gray-500 text-[10px] font-bold tracking-widest uppercase">Pacífico ERP</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(Auth::user()->rol === 'admin' || Auth::user()->rol === 'egresos')
                        <x-nav-link :href="route('egresos.index')" :active="request()->routeIs('egresos.*')">
                            💰 Caja y Egresos
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->rol === 'admin' || Auth::user()->rol === 'ventas')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            📊 Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('ventas.index')" :active="request()->routeIs('ventas.*')">
                            📈 Ventas
                        </x-nav-link>

                        <x-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                            📦 Almacén
                        </x-nav-link>

                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                            👥 Clientes
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                
                <div class="relative" x-data="{ 
                    openNotif: false, 
                    notificaciones: [],
                    init() {
                        fetch('{{ route('notificaciones.index') }}')
                            .then(res => res.json())
                            .then(data => this.notificaciones = data);
                    },
                    marcarComoPagado(ventaId, tipoVenta, notificationId) {
                        // Hace el cobro real en la base de datos de forma asíncrona
                        fetch(`/ventas/${ventaId}/cancelar-credito`, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                tipo_venta: tipoVenta,
                                notification_id: notificationId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Remueve la notificación de la lista visual en tiempo real
                                this.notificaciones = this.notificaciones.filter(n => n.id !== notificationId);
                                
                                // Si el usuario está parado en la pantalla de gestión de ventas, recargamos para actualizar las tablas abajo
                                if (window.location.pathname.includes('/ventas')) {
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }" @click.away="openNotif = false">
                    
                    <button @click="openNotif = !openNotif" class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-150 rounded-full hover:bg-gray-50">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        
                        <template x-if="notificaciones.length > 0">
                            <span class="absolute top-1 right-1 block h-4 w-4 rounded-full bg-red-500 text-[10px] font-bold text-white text-center leading-4 animate-pulse" x-text="notificaciones.length"></span>
                        </template>
                    </button>

                    <div x-show="openNotif" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 rounded-xl bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 z-50 border border-gray-100" 
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-gray-100 font-bold text-xs text-gray-700 uppercase tracking-wider bg-gray-50/50">
                            ⏳ Créditos por Cobrar Vencidos
                        </div>
                        
                        <div class="max-h-64 overflow-y-auto split-y divide-y divide-gray-100">
                            <template x-if="notificaciones.length === 0">
                                <p class="px-4 py-6 text-sm text-gray-400 text-center italic">¡Al día! No hay cuentas pendientes hoy.</p>
                            </template>

                            <template x-for="notif in notificaciones" :key="notif.id">
                                <div class="px-4 py-3 hover:bg-slate-50 transition duration-150 flex flex-col gap-1.5">
                                    <div class="text-xs text-gray-700 font-medium leading-normal" x-text="notif.data.mensaje"></div>
                                    <div class="flex justify-end">
                                        <button @click="marcarComoPagado(notif.data.venta_id, notif.data.tipo_venta, notif.id)" class="text-[11px] text-emerald-600 hover:text-emerald-800 font-bold tracking-wide uppercase transition-colors flex items-center gap-1">
                                            💰 Marcar como Pagado
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full uppercase">
                                    {{ Auth::user()->rol ?? 'User' }}
                                </span>
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            👤 Mi Perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                🚪 Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden shadow-inner bg-gray-50">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->rol === 'admin' || Auth::user()->rol === 'egresos')
                <x-responsive-nav-link :href="route('egresos.index')" :active="request()->routeIs('egresos.*')">
                    💰 Caja y Egresos
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->rol === 'admin' || Auth::user()->rol === 'ventas')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    📊 Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('ventas.index')" :active="request()->routeIs('ventas.*')">
                    📈 Ventas
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                    📦 Almacén
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                    👥 Clientes
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 bg-white">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    👤 Mi Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        🚪 Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>