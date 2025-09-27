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
                <a href="{{ route('secretarias.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">👥
                    Secretarias</a>
                <!-- vincular cargos com a secretarias -->
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
</body>

</html>
