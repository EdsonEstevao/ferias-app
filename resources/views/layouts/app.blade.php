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
    </style>
</head>

<body class="text-gray-800 bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Menu lateral --}}
        <aside class="w-64 bg-white shadow-md mobile-sidebar md:block">
            <div class="flex items-center justify-between p-6 text-xl font-bold border-b">
                <span>Painel</span>
                <button id="close-menu" class="text-gray-500 md:hidden hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="p-4 space-y-2 text-sm">
                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 rounded {{ request()->routeIs('dashboard') ? ' bg-gray-600 text-amber-100' : '' }}">🏠
                    Dashboard</a>
                <!-- icon servidores -->

                <a href="{{ route('servidores.index') }}"
                    class="block px-4 py-2 rounded {{ request()->routeIs('servidores.index') ? ' bg-gray-600 text-amber-100' : '' }} ">
                    <i class="text-blue-400 fa-solid fa-users"></i>
                    Servidores</a>

                <!-- Menu com submenu -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2 rounded {{ request()->routeIs('secretarias.index') ? ' bg-gray-600 text-amber-100' : '' }} {{ request()->routeIs('secretarias.create') ? ' bg-gray-600 text-amber-100' : '' }} focus:outline-none">
                        <!-- icon secretarias -->

                        <span><i class="text-yellow-300 fa-brands fa-fort-awesome"></i> Secretarias</span>
                        <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                        <a href="{{ route('secretarias.index') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-600 text-amber-300 transition-all ease-linear duration-300"><i
                                class="fa-solid fa-bars-staggered "></i> Listar Secretarias</a>
                        <a href="{{ route('secretarias.create') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-600 hover:text-indigo-200 text-indigo-600 transition-all ease-linear duration-300"><i
                                class="fa-solid fa-circle-plus "></i> Adicionar Secretaria</a>
                    </div>
                </div>
                <!-- icon administrar cargos -->
                <a href="{{ route('vinculo.cargos.secretarias') }}"
                    class="block px-4 py-2 rounded {{ request()->routeIs('vinculo.cargos.secretarias') ? ' bg-gray-600 text-amber-100' : '' }} ">
                    <i class="text-green-300 fas fa-users-cog"></i>
                    Administrar Cargos</a>

                <!-- Menu com submenu Férias -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2 rounded  focus:outline-none  {{ request()->routeIs('ferias.index') ? ' bg-gray-600 text-amber-100' : '' }} {{ request()->routeIs('ferias.import') ? ' bg-gray-600 text-amber-100' : '' }}">
                        <!-- icon ferias -->

                        <span><i class="text-red-400 fas fa-calendar"></i> Ferias</span>
                        <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                        <a href="{{ route('ferias.index') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-600 text-amber-300 hover:text-yellow-200 transition-all ease-linear duration-300">
                            📅 Listar Férias</a>
                        <a href="{{ route('ferias.import') }}"
                            class="block px-4 py-2 rounded hover:bg-gray-600 hover:text-indigo-200 transition-all ease-linear duration-300">📅
                            Importar
                            Ferias (.Csv)</a>
                    </div>
                </div>

                <!-- Menu com submenu roles admin -->

                <!-- permissão somente para administradores visualizar -->
                @role('admin|super admin')
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-2 rounded focus:outline-none {{ request()->routeIs('admin.users.index') ? ' bg-gray-600 text-amber-100' : '' }} {{ request()->routeIs('admin.users.create') ? ' bg-gray-600 text-amber-100' : '' }}">
                            <!-- icon usuarios -->

                            <span><i class="text-blue-600 fas fa-users"></i> Usuarios</span>
                            <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                            <a href="{{ route('admin.users.index') }}"
                                class="block px-4 py-2 rounded hover:bg-gray-600 text-amber-300 transition-all ease-linear duration-300"><i
                                    class="fa-solid fa-bars-staggered "></i> Listar Usuarios</a>
                            <a href="{{ route('admin.users.create') }}"
                                class="block px-4 py-2 rounded hover:bg-gray-600 hover:text-indigo-200 text-indigo-600 transition-all ease-linear duration-300"><i
                                    class="fa-solid fa-user-plus"></i>
                                Adicionar Usuario</a>
                        </div>
                    </div>
                    <!-- Menu com submenu Role -->
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-2 rounded  focus:outline-none  {{ request()->routeIs('admin.roles.index') ? ' bg-gray-600 text-amber-100' : '' }} {{ request()->routeIs('admin.roles.create') ? ' bg-gray-600 text-amber-100' : '' }}">
                            <!-- icon role -->
                            <span><i class="text-red-400 fas fa-user-tag"></i> Role</span>
                            <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                            <a href="{{ route('admin.roles.index') }}"
                                class="block px-4 py-2 rounded hover:bg-gray-600 text-amber-300 transition-all ease-linear duration-300"><i
                                    class="fa-solid fa-bars-staggered "></i> Listar Roles</a>
                            <a href="{{ route('admin.roles.store') }}"
                                class="block px-4 py-2 rounded hover:bg-gray-600 hover:text-indigo-300 text-indigo-600 transition-all ease-linear duration-300"><i
                                    class="fa-solid fa-person-circle-plus"></i> Adicionar Role</a>
                        </div>
                    </div>
                @endrole
                <!-- permissão somente para super administradores visualizar -->
                @role('super admin')
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-2 rounded {{ request()->routeIs('admin.audit.index') ? ' bg-gray-600 text-amber-100' : '' }}  focus:outline-none">
                            <!-- icon auditoria -->
                            <span><i class="fa-duotone fa-solid fa-audio-description text-lime-700"></i> Auditoria</span>
                            <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">
                            <a href="{{ route('admin.audit.index') }}"
                                class="block px-4 py-2 rounded hover:bg-gray-200"><i
                                    class="fa-duotone fa-solid fa-users-viewfinder text-red-500"></i> Visualizar</a>

                        </div>
                    </div>
                @endrole
                <!-- Menu com submenu Perfil -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2 rounded {{ request()->routeIs('profile.edit') ? ' bg-gray-600 text-amber-100' : '' }} focus:outline-none">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full">
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-6 space-y-1 text-gray-700">

                        <x-responsive-nav-link :href="route('profile.edit')">
                            Perfil
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Sair
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>

            </nav>
        </aside>

        {{-- Overlay para fechar o menu ao clicar fora --}}
        <div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

        {{-- Conteúdo principal --}}
        <div class="flex flex-col flex-1 overflow-y-auto">

            {{-- Cabeçalho fixo --}}
            <header class="sticky top-0 z-10 bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="hamburger-button" class="hamburger-button">
                        <i class="fas fa-bars"></i>
                    </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerButton = document.getElementById('hamburger-button');
            const closeMenuButton = document.getElementById('close-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const sidebar = document.querySelector('.mobile-sidebar');

            function openMenu() {
                sidebar.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Previne scroll no body
            }

            function closeMenu() {
                sidebar.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = ''; // Restaura scroll no body
            }

            hamburgerButton.addEventListener('click', openMenu);
            closeMenuButton.addEventListener('click', closeMenu);
            mobileMenuOverlay.addEventListener('click', closeMenu);

            // Fechar menu ao clicar em um link (para mobile)
            const menuLinks = document.querySelectorAll('.mobile-sidebar a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeMenu();
                    }
                });
            });

            // Fechar menu ao redimensionar a janela para desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    closeMenu();
                }
            });
        });
    </script>
</body>

</html>
