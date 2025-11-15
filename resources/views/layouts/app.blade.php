<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noaudio">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Férias' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons Font Awesome -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github.min.css"> --}}

    <!-- Icons Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/awesome-all.min.css') }}">
    <!-- Highlight.js -->
    <link rel="stylesheet" href="{{ asset('css/highlight.min.css') }}">

    <style>
        /* Estilos para o overlay do menu móvel */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }

        .mobile-menu-overlay.active {
            display: block;
        }

        /* Transição suave para o menu móvel */
        .mobile-sidebar {
            transition: transform 0.3s ease-in-out;
        }

        /* Estilo para o botão hamburger */
        .hamburger-button {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Cores harmoniosas para a interface */
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
        }

        .dark {
            --primary-color: #60a5fa;
            --primary-hover: #3b82f6;
            --secondary-color: #94a3b8;
            --accent-color: #fbbf24;
            --success-color: #34d399;
            --background-color: #0f172a;
            --surface-color: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #334155;
        }

        @media (max-width: 767px) {
            .hamburger-button {
                display: block;
            }

            .mobile-sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 50;
                overflow-y: auto;
            }

            .mobile-sidebar.active {
                transform: translateX(0);
            }
        }

        /* Estilos melhorados para paginação */
        .pagination-btn {
            @apply px-4 py-2 text-sm font-medium transition-all duration-200 ease-in-out border rounded-lg;
        }

        .pagination-btn-primary {
            @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:border-blue-700;
        }

        .pagination-btn-secondary {
            @apply bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700;
        }

        .pagination-btn-disabled {
            @apply opacity-50 cursor-not-allowed bg-gray-100 text-gray-400 border-gray-200 dark:bg-gray-700 dark:text-gray-500 dark:border-gray-600;
        }

        .pagination-active {
            @apply bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900 dark:text-blue-300 dark:border-blue-700;
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Menu lateral --}}
        <aside class="w-64 bg-white shadow-lg mobile-sidebar md:block dark:bg-slate-800">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <span class="text-xl font-bold text-slate-800 dark:text-slate-100">Painel</span>
                <button id="close-menu" class="text-slate-500 md:hidden hover:text-slate-700 dark:hover:text-slate-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="p-4 space-y-1 text-sm">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard')
                        ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                        : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                    <i class="w-5 mr-3 text-blue-500 fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Servidores -->
                <div x-data="{ open: {{ request()->routeIs('servidores.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.*')
                            ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                        <div class="flex items-center">
                            <i class="w-5 mr-3 text-indigo-500 fas fa-users"></i>
                            <span>Servidores</span>
                        </div>
                        <svg :class="{ 'transform rotate-90': open }"
                            class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1">
                        <a href="{{ route('servidores.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.index')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-list"></i>
                            Listar
                        </a>
                        <a href="{{ route('servidores.por-departamento') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.por-departamento')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-building"></i>
                            Por Departamento
                        </a>
                        <a href="{{ route('servidores.nomeados.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.nomeados.*')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-user-tie"></i>
                            Servidores Nomeados
                        </a>
                        <a href="{{ route('servidores.exoneracao.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.exoneracao.*')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-user-slash"></i>
                            Servidores Exonerados
                        </a>
                        @role('super admin')
                            <!-- Importar Servidores -->
                            <a href="{{ route('servidores.import.form') }}"
                                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('servidores.import.*')
                                    ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                                    : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                                <i class="w-5 mr-3 text-cyan-500 fas fa-file-import"></i>
                                <span>Importar Servidores</span>
                            </a>
                        @endrole
                    </div>
                </div>

                <!-- Secretarias -->
                <div x-data="{ open: {{ request()->routeIs('secretarias.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('secretarias.*')
                            ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                        <div class="flex items-center">
                            <i class="w-5 mr-3 text-amber-500 fas fa-landmark"></i>
                            <span>Secretarias</span>
                        </div>
                        <svg :class="{ 'transform rotate-90': open }"
                            class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1">
                        <a href="{{ route('secretarias.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('secretarias.index')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-list"></i>
                            Listar Secretarias
                        </a>
                        <a href="{{ route('secretarias.create') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('secretarias.create')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-plus-circle"></i>
                            Adicionar Secretaria
                        </a>
                    </div>
                </div>

                <!-- Administrar Cargos -->
                <a href="{{ route('vinculo.cargos.secretarias') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('vinculo.cargos.secretarias')
                        ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                        : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                    <i class="w-5 mr-3 text-emerald-500 fas fa-users-cog"></i>
                    <span>Administrar Cargos</span>
                </a>

                <!-- Férias -->
                <div x-data="{ open: {{ request()->routeIs('ferias.*') || request()->routeIs('ferias-import.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias.*') || request()->routeIs('ferias-import.*')
                            ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                        <div class="flex items-center">
                            <i class="w-5 mr-3 text-red-400 fas fa-calendar-alt"></i>
                            <span>Férias</span>
                        </div>
                        <svg :class="{ 'transform rotate-90': open }"
                            class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1">
                        <a href="{{ route('ferias.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias.index')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-list"></i>
                            Listar Férias
                        </a>
                        <a href="{{ route('ferias.filtro') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias.filtro')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-search"></i>
                            Filtro de Férias
                        </a>
                        <a href="{{ route('ferias.import') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias.import')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-file-csv"></i>
                            Importar CSV
                        </a>
                        <a href="{{ route('ferias-import.create') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias-import.create')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-file-import"></i>
                            Importar JSON
                        </a>
                        <a href="{{ route('ferias-import.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ferias-import.index')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-history"></i>
                            Histórico
                        </a>


                    </div>
                </div>

                <!-- Admin Section -->
                @role('admin|super admin')
                    <!-- Usuários -->
                    <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*')
                                ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                                : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                            <div class="flex items-center">
                                <i class="w-5 mr-3 text-blue-600 fas fa-user-friends"></i>
                                <span>Usuários</span>
                            </div>
                            <svg :class="{ 'transform rotate-90': open }"
                                class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-8 space-y-1">
                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.index')
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                                <i class="w-4 mr-2 fas fa-list"></i>
                                Listar Usuários
                            </a>
                            <a href="{{ route('admin.users.create') }}"
                                class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.create')
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                                <i class="w-4 mr-2 fas fa-user-plus"></i>
                                Adicionar Usuário
                            </a>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div x-data="{ open: {{ request()->routeIs('admin.roles.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.*')
                                ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                                : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                            <div class="flex items-center">
                                <i class="w-5 mr-3 text-purple-500 fas fa-user-tag"></i>
                                <span>Roles</span>
                            </div>
                            <svg :class="{ 'transform rotate-90': open }"
                                class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-8 space-y-1">
                            <a href="{{ route('admin.roles.index') }}"
                                class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.index')
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                                <i class="w-4 mr-2 fas fa-list"></i>
                                Listar Roles
                            </a>
                            <a href="{{ route('admin.roles.store') }}"
                                class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.store')
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                                <i class="w-4 mr-2 fas fa-plus-circle"></i>
                                Adicionar Role
                            </a>
                        </div>
                    </div>
                @endrole

                @role('super admin')
                    <!-- Auditoria -->
                    <div x-data="{ open: {{ request()->routeIs('admin.audit.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit.*')
                                ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                                : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                            <div class="flex items-center">
                                <i class="w-5 mr-3 text-lime-600 fas fa-shield-alt"></i>
                                <span>Auditoria</span>
                            </div>
                            <svg :class="{ 'transform rotate-90': open }"
                                class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-8 space-y-1">
                            <a href="{{ route('admin.audit.index') }}"
                                class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit.index')
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                                <i class="w-4 mr-2 fas fa-eye"></i>
                                Visualizar Auditoria
                            </a>
                        </div>
                    </div>
                @endrole

                <!-- Perfil -->
                <div x-data="{ open: {{ request()->routeIs('profile.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*')
                            ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700'
                            : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                        <div class="flex items-center">
                            <div class="w-2 h-2 mr-3 bg-green-500 rounded-full animate-pulse" title="Online"></div>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <svg :class="{ 'transform rotate-90': open }"
                            class="w-4 h-4 transition-transform text-slate-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.edit')
                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-800/50 dark:text-blue-300'
                                : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50' }}">
                            <i class="w-4 mr-2 fas fa-user-cog"></i>
                            Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-left transition-all duration-200 rounded-lg text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-700/50">
                                <i class="w-4 mr-2 fas fa-sign-out-alt"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Contador de usuários online -->
            <div
                class="absolute bottom-0 left-0 flex items-center w-full p-4 text-xs border-t bg-slate-50 border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                <div class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-slate-600 dark:text-slate-400">Online:</span>
                <strong class="ml-1 text-slate-800 dark:text-slate-200" id="onlineUsersCount">
                    {{ \App\Models\User::online()->count() }}
                </strong>
            </div>
        </aside>

        {{-- Overlay para fechar o menu ao clicar fora --}}
        <div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

        {{-- Conteúdo principal --}}
        <div class="flex flex-col flex-1 overflow-y-auto">
            {{-- Cabeçalho --}}
            <header class="sticky top-0 z-10 bg-white shadow-sm dark:bg-slate-800">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="hamburger-button" class="hamburger-button text-slate-600 dark:text-slate-300">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Sistema de Gestão de Férias
                    </h1>

                    <div class="flex items-center space-x-4">
                        <!-- Contador de usuários online com dropdown -->
                        <div x-data="{ showOnlineUsers: false }" class="relative">
                            <button @click="showOnlineUsers = !showOnlineUsers"
                                class="flex items-center px-3 py-2 text-sm transition-colors rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                                <div class="w-2 h-2 mr-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span>Online: </span>
                                <strong class="ml-1" id="onlineUsersCount">
                                    {{ \App\Models\User::online()->count() }}
                                </strong>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown com lista de usuários online -->
                            <div x-show="showOnlineUsers" @click.away="showOnlineUsers = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 z-50 w-64 mt-2 bg-white border rounded-lg shadow-lg dark:bg-slate-800 dark:border-slate-700">
                                <div class="p-3 border-b dark:border-slate-700">
                                    <h3 class="font-semibold text-slate-900 dark:text-slate-100">Usuários Online</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Ativos nos últimos 5 minutos
                                    </p>
                                </div>
                                <div class="overflow-y-auto max-h-60">
                                    @forelse(\App\Models\User::online()->get() as $user)
                                        <div
                                            class="flex items-center px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                            <div class="w-2 h-2 mr-2 rounded-full bg-emerald-500"></div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium truncate text-slate-900 dark:text-slate-100">
                                                    {{ $user->name }}</p>
                                                <p class="text-xs truncate text-slate-500 dark:text-slate-400">
                                                    {{ $user->email }}</p>
                                            </div>
                                            <span class="ml-2 text-xs text-slate-400 dark:text-slate-500">
                                                {{ $user->last_activity_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="px-3 py-4 text-center text-slate-500 dark:text-slate-400">
                                            <p>Nenhum usuário online</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-slate-600 dark:text-slate-300">Olá, {{ auth()->user()->name }}</div>
                    </div>
                </div>
            </header>

            {{-- Conteúdo dinâmico --}}
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/bf39cb216e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerButton = document.getElementById('hamburger-button');
            const closeMenuButton = document.getElementById('close-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const sidebar = document.querySelector('.mobile-sidebar');

            function openMenu() {
                sidebar.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                sidebar.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            hamburgerButton.addEventListener('click', openMenu);
            closeMenuButton.addEventListener('click', closeMenu);
            mobileMenuOverlay.addEventListener('click', closeMenu);

            const menuLinks = document.querySelectorAll('.mobile-sidebar a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeMenu();
                    }
                });
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    closeMenu();
                }
            });
        });

        // Atualização segura da contagem de usuários online
        function atualizarContagemOnline() {
            const userToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!userToken) return;

            fetch('/online-users-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': userToken
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta da API');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const elements = document.querySelectorAll('#onlineUsersCount');
                        elements.forEach(element => {
                            element.textContent = data.count ?? 0;
                        });
                    }
                })
                .catch(error => {
                    console.log('Erro ao atualizar contagem online:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(atualizarContagemOnline, 2000);
            setInterval(atualizarContagemOnline, 45000);

            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    setTimeout(atualizarContagemOnline, 1000);
                }
            });
        });
    </script>
</body>

</html>
