{{-- <x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 text-center bg-white rounded-lg shadow-md">
            <div class="mb-4 text-6xl">🔒</div>
            <h1 class="mb-2 text-2xl font-bold text-gray-800">Acesso Negado</h1>
            <p class="mb-6 text-gray-600">
                Você não tem permissão para acessar esta página.
            </p>

            @if (session('error'))
                <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col space-y-3">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700">
                    Voltar ao Dashboard
                </a>

                @if (auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 underline hover:text-gray-800">
                            Fazer Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 underline hover:text-blue-800">
                        Fazer Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout> --}}

<x-errors-layout title="Acesso Negado" type="warning">
    <p class="text-gray-600 error-message">
        Você não tem permissão para acessar esta página.
        Entre em contato com o administrador do sistema.
    </p>

    @slot('code')
        ERROR_403_FORBIDDEN
    @endslot
</x-errors-layout>
