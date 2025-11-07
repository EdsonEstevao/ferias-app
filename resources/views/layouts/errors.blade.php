{{-- resources/views/layouts/errors.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-xl">
        <!-- Ícone -->
        <div class="mb-6 text-center">
            @if ($type === 'error')
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            @elseif($type === 'warning')
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            @else
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            @endif
        </div>

        <!-- Título -->
        <h1 class="mb-4 text-2xl font-bold text-center text-gray-900">
            {{ $title }}
        </h1>

        <!-- Conteúdo -->
        <div class="mb-6 text-center text-gray-600">
            {{ $slot }}
        </div>
        <!-- Mensagem -->
        <p class="mb-6 text-center text-gray-700">
            {{ $message }}
        </p>

        <!-- Ações -->
        <div class="flex flex-col gap-3">
            <a href="{{ url()->previous() }}"
                class="px-4 py-2 text-center text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                ← Voltar
            </a>

            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-center text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                🏠 Ir para o Dashboard
            </a>
        </div>

        <!-- Contato -->
        @if ($showContact)
            <div class="pt-6 mt-6 text-sm text-center text-gray-500 border-t border-gray-200">
                Precisando de ajuda? <a href="mailto:suporte@exemplo.com" class="text-blue-600 hover:underline">Contate
                    o suporte</a>
            </div>
        @endif
    </div>
</body>

</html>
