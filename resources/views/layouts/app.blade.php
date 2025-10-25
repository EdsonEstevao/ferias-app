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
                            class="block px-4 py-2 transition-all duration-300 ease-linear rounded hover:bg-gray-600 text-amber-300"><i
                                class="fa-solid fa-bars-staggered "></i> Listar Secretarias</a>
                        <a href="{{ route('secretarias.create') }}"
                            class="block px-4 py-2 text-indigo-600 transition-all duration-300 ease-linear rounded hover:bg-gray-600 hover:text-indigo-200"><i
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
                            class="block px-4 py-2 transition-all duration-300 ease-linear rounded hover:bg-gray-600 text-amber-300 hover:text-yellow-200">
                            📅 Listar Férias</a>
                        <a href="{{ route('ferias.import') }}"
                            class="block px-4 py-2 transition-all duration-300 ease-linear rounded hover:bg-gray-600 hover:text-indigo-200">📅
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
                                class="block px-4 py-2 transition-all duration-300 ease-linear rounded hover:bg-gray-600 text-amber-300"><i
                                    class="fa-solid fa-bars-staggered "></i> Listar Usuarios</a>
                            <a href="{{ route('admin.users.create') }}"
                                class="block px-4 py-2 text-indigo-600 transition-all duration-300 ease-linear rounded hover:bg-gray-600 hover:text-indigo-200"><i
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
                                class="block px-4 py-2 transition-all duration-300 ease-linear rounded hover:bg-gray-600 text-amber-300"><i
                                    class="fa-solid fa-bars-staggered "></i> Listar Roles</a>
                            <a href="{{ route('admin.roles.store') }}"
                                class="block px-4 py-2 text-indigo-600 transition-all duration-300 ease-linear rounded hover:bg-gray-600 hover:text-indigo-300"><i
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
                                    class="text-red-500 fa-duotone fa-solid fa-users-viewfinder"></i> Visualizar</a>

                        </div>
                    </div>
                @endrole
                <!-- Menu com submenu Perfil -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2 rounded {{ request()->routeIs('profile.edit') ? ' bg-gray-600 text-amber-100' : '' }} focus:outline-none">
                        <div class="flex items-center gap-2">
                            {{-- <div class="w-2 h-2 bg-green-400 rounded-full">
                            </div> --}}
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Você está online">

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
            <!-- Contador seguro de usuários online -->
            <!-- deixa o contador visivel no final do menu abaixo-->
            @auth
                {{-- <div class="flex items-center px-3 py-1 text-xs text-gray-600 border rounded-lg bg-gray-50 position-fixed bottom-2 right-2" --}}
                <div class="absolute bottom-0 left-0 flex items-center w-full px-3 py-1 text-xs text-gray-600 border bg-gray-50"
                    title="Usuários ativos nos últimos 5 minutos">
                    <div class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span>Online: </span>
                    <strong class="ml-1" id="onlineUsersCount">
                        {{-- {{ getOnlineUsersCount() }} --}}
                    </strong>
                    {{-- teste --}}

                </div>
            @endauth
        </aside>

        {{-- Overlay para fechar o menu ao clicar fora --}}
        <div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

        {{-- Conteúdo principal --}}
        <div class="flex flex-col flex-1 overflow-y-auto">

            {{-- Cabeçalho fixo --}}
            {{-- <header class="sticky top-0 z-10 bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="hamburger-button" class="hamburger-button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-semibold">Sistema de Gestão de Férias</h1>
                    <div class="text-sm text-gray-600">Olá, {{ auth()->user()->name }}</div>
                </div>

            </header> --}}
            {{-- Cabeçalho fixo --}}
            {{-- Cabeçalho fixo --}}
            <header class="sticky top-0 z-10 bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="hamburger-button" class="hamburger-button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-semibold">Sistema de Gestão de Férias</h1>

                    <div class="flex items-center space-x-4">
                        <!-- Contador de usuários online com dropdown -->
                        <div x-data="{ showOnlineUsers: false }" class="relative">
                            <button @click="showOnlineUsers = !showOnlineUsers"
                                class="flex items-center px-3 py-1 text-sm text-gray-600 transition-colors rounded-full bg-green-50 hover:bg-green-100">
                                <div class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-pulse"></div>
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
                                class="absolute right-0 z-50 w-64 mt-2 bg-white border rounded-lg shadow-lg">
                                <div class="p-3 border-b">
                                    <h3 class="font-semibold text-gray-900">Usuários Online</h3>
                                    <p class="text-xs text-gray-500">Ativos nos últimos 5 minutos</p>
                                </div>
                                <div class="overflow-y-auto max-h-60">
                                    @forelse(\App\Models\User::online()->get() as $user)
                                        <div class="flex items-center px-3 py-2 hover:bg-gray-50">
                                            <div class="w-2 h-2 mr-2 bg-green-500 rounded-full"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $user->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                            </div>
                                            <span
                                                class="ml-2 text-xs text-gray-400">{{ $user->last_activity_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="px-3 py-4 text-center text-gray-500">
                                            <p>Nenhum usuário online</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600">Olá, {{ auth()->user()->name }}</div>
                    </div>
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
                    console.log(data);
                    if (data.status === 'success') {
                        const element = document.getElementById('onlineUsersCount');
                        if (element) {
                            element.textContent = data.count ?? 0;
                        }
                    }
                })
                .catch(error => {
                    console.log('Erro ao atualizar contagem online:', error);
                    // Não mostrar erro para o usuário, é uma funcionalidade não crítica
                });
        }

        // Atualizar de forma segura
        document.addEventListener('DOMContentLoaded', function() {
            // Esperar 2 segundos após o carregamento
            setTimeout(atualizarContagemOnline, 2000);

            // Atualizar a cada 45 segundos (não muito frequente)
            setInterval(atualizarContagemOnline, 45000);

            // Atualizar quando a página ganha foco
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    setTimeout(atualizarContagemOnline, 1000);
                }
            });
        });
    </script>
    {{-- <script>
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
                        const element = document.getElementById('onlineUsersCount');
                        if (element) {
                            element.textContent = data.count;
                        }
                    }
                })
                .catch(error => {
                    console.log('Erro ao atualizar contagem online:', error);
                    // Não mostrar erro para o usuário, é uma funcionalidade não crítica
                });
        }

        // Atualizar de forma segura
        document.addEventListener('DOMContentLoaded', function() {
            // Esperar 2 segundos após o carregamento
            setTimeout(atualizarContagemOnline, 2000);

            // Atualizar a cada 45 segundos (não muito frequente)
            setInterval(atualizarContagemOnline, 45000);

            // Atualizar quando a página ganha foco
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    setTimeout(atualizarContagemOnline, 1000);
                }
            });
        });
    </script> --}}
</body>

</html>
