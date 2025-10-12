<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Férias' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- Icons Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body class="text-gray-800 bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Menu lateral --}}
        <aside class="hidden w-64 bg-white shadow-md md:block">
            <div class="p-6 text-xl font-bold border-b">Painel</div>
            <nav class="p-4 space-y-2 text-sm">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100">🏠 Dashboard</a>

                <a href="{{ route('servidores.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">👥
                    Servidores</a>

                <!-- Menu com submenu -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2 rounded hover:bg-gray-100 focus:outline-none">
                        <span>👥 Secretarias</span>
                        <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                        <a href="{{ route('secretarias.index') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-200">Listar Secretarias</a>
                        <a href="{{ route('secretarias.create') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-200">Adicionar Secretaria</a>
                    </div>
                </div>

                <a href="{{ route('vinculo.cargos.secretarias') }}" class="block px-4 py-2 rounded hover:bg-gray-100">👥
                    Administrar Cargos</a>

                <a href="{{ route('ferias.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">📅 Férias</a>

                <a href="{{ route('ferias.import') }}" class="block px-4 py-2 rounded hover:bg-gray-100">📅 Importar
                    Ferias (.Csv)</a>

                <a href="{{ route('logout') }}" class="block px-4 py-2 text-red-600 rounded hover:bg-red-100">🚪
                    Sair</a>
            </nav>
        </aside>

        {{-- Conteúdo principal --}}
        <div class="flex flex-col flex-1 overflow-y-auto">

            {{-- Cabeçalho fixo --}}
            <header class="sticky top-0 z-10 bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-lg font-semibold">Sistema de Gestão de Férias</h1>
                    <div class="text-sm text-gray-600">Olá, {{ auth()->user()->name }}</div>
                </div>
            </header>

            {{-- Conteúdo dinâmico --}}
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/bf39cb216e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>
