<x-guest-layout>
    <div class="text-center text-gray-900"> <h1 class="text-2xl font-bold mb-4">🚧 Módulo en Construcción 🚧</h1>
        
        <p class="mb-6 text-lg">
            Hola <b>{{ Auth::user()->name }}</b>,<br>
            Tu módulo de <b>{{ strtoupper(Auth::user()->rol) }}</b> todavía no está listo.
        </p>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                Cerrar Sesión y Salir
            </button>
        </form>

    </div>
</x-guest-layout>