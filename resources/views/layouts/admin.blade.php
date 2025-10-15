<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | {{ $title ?? '' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin"
        rel="stylesheet" type="text/css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <style>
        html {
            font-size: 90% !important;
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }

        a[href="#top"] {
            position: fixed;
            bottom: 50px;
            right: 10px;
            font-size: 22px;
            width: 35px;
            height: 35px;
            padding: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            outline: none !important;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 19;
        }

        a[href="#top"]:hover {
            text-decoration: none;
        }

        a[href="#top"] img {
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800" x-data="state" x-init="init()">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('state', () => ({
                sidebarOpen: false,
                collapsed: false,
                isDesktop: window.innerWidth >= 1024,

                init() {
                    // 🔹 Carrega o estado salvo do menu
                    const saved = localStorage.getItem('sidebar-collapsed');
                    if (saved !== null) this.collapsed = JSON.parse(saved);

                    const sync = () => {
                        this.isDesktop = window.innerWidth >= 1024;

                        if (!this.isDesktop) {
                            // No mobile o menu sempre fechado
                            this.sidebarOpen = false;
                            this.collapsed = false;
                        } else {
                            // No desktop, respeita o valor salvo
                            this.sidebarOpen = true;
                        }
                    };

                    // Atualiza comportamento em redimensionamentos
                    window.addEventListener('resize', sync);
                    sync();

                    // ESC fecha o menu apenas no mobile
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !this.isDesktop && this.sidebarOpen) {
                            this.sidebarOpen = false;
                        }
                    });

                    // 🔹 Observa mudanças e salva no localStorage
                    this.$watch('collapsed', (value) => {
                        localStorage.setItem('sidebar-collapsed', JSON.stringify(value));
                    });
                }
            }))
        });
    </script>

    <div class="flex min-h-screen" x-cloak>
        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white flex flex-col transition-all duration-300" x-data="{ hoverIndex: null }"
            :class="{
                // === Desktop ===
                'fixed w-60 flex-shrink-0': isDesktop && !collapsed,
                'w-16 flex-shrink-0': isDesktop && collapsed,
            
                // === Mobile ===
                'fixed inset-0 z-40 w-60 transform': !isDesktop,
                'translate-x-0': !isDesktop && sidebarOpen,
                '-translate-x-full': !isDesktop && !sidebarOpen
            }"
            style="height: 100vh">
            <!-- Header da sidebar -->
            <div class="p-4 border-b border-blue-800 flex items-center justify-between">
                <div class="flex items-center space-x-1">
                    <i class="fa-solid fa-stethoscope text-1xl"></i>
                    <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity
                        class="font-bold text-md truncate">
                        {{ config('app.name') }}
                    </span>
                </div>

                <button @click="sidebarOpen = false" class="lg:hidden text-blue-200 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Navegação -->
            <nav class="flex-1 p-3 space-y-1 mt-4">
                @php
                    $user = auth()->user();
                    $links = [
                        [
                            'label' => 'Dashboard',
                            'route' => 'admin.dashboard',
                            'active' => 'admin.dashboard',
                            'icon' => 'fa-solid fa-gauge',
                        ], // painel geral
                        [
                            'label' => 'Setores',
                            'route' => 'admin.setores.listagem',
                            'active' => 'admin.setores*',
                            'icon' => 'fa-solid fa-building',
                        ], // setor/empresa
                        [
                            'label' => 'Tipos de Procedimento',
                            'route' => 'admin.tipos_procedimento.listagem',
                            'active' => 'admin.tipos_procedimento*',
                            'icon' => 'fa-solid fa-list-check',
                        ], // lista de tipos
                        [
                            'label' => 'Procedimentos',
                            'route' => 'admin.procedimentos.listagem',
                            'active' => 'admin.procedimentos*',
                            'icon' => 'fa-solid fa-stethoscope',
                        ], // procedimento médico
                        [
                            'label' => 'Equipes de Saúde',
                            'route' => 'admin.equipes_saude.listagem',
                            'active' => 'admin.equipes_saude*',
                            'icon' => 'fa-solid fa-house-medical',
                        ], // equipe de saúde da família
                        [
                            'label' => 'Agentes de Saúde',
                            'route' => 'admin.agentes_saude.listagem',
                            'active' => 'admin.agentes_saude*',
                            'icon' => 'fa-solid fa-user-nurse',
                        ], // agente comunitário de saúde
                        [
                            'label' => 'Pacientes',
                            'route' => 'admin.pacientes.listagem',
                            'active' => 'admin.pacientes*',
                            'icon' => 'fa-solid fa-user-injured',
                        ], // paciente
                        [
                            'label' => 'Atendimentos',
                            'route' => 'admin.atendimentos.listagem',
                            'active' => 'admin.atendimentos*',
                            'icon' => 'fa-solid fa-calendar-check',
                        ], // atendimentos/agenda
                        [
                            'label' => 'Solicitações',
                            'route' => 'admin.solicitacoes.listagem',
                            'active' => 'admin.solicitacoes*',
                            'icon' => 'fa-solid fa-file-medical',
                        ], // solicitações médicas
                        [
                            'label' => 'Movimentações',
                            'route' => 'admin.movimentacoes.formulario',
                            'active' => 'admin.movimentacoes*',
                            'icon' => 'fa-solid fa-right-left',
                        ], // Movimentações de solicitações em massa
                    ];
                    if ($user && $user->isAdmin()) {
                        $links = array_merge($links, [
                            [
                                'label' => 'Usuários',
                                'route' => 'admin.usuarios.listagem',
                                'active' => 'admin.usuarios*',
                                'icon' => 'fa-solid fa-users',
                            ],
                        ]);
                    }
                @endphp

                @foreach ($links as $index => $link)
                    @php
                        $active = request()->routeIs($link['active'])
                            ? 'bg-blue-700 text-white'
                            : 'text-blue-100 hover:bg-blue-800';
                    @endphp

                    <a href="{{ route($link['route']) }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = {{ $index }}" @mouseleave="hoverIndex = null">

                        <!-- Ícone: sempre visível -->
                        <i class="{{ $link['icon'] }} text-md min-w-[1.5rem] text-center"></i>

                        <!-- Texto: aparece apenas no hover do menu colapsado -->
                        <span x-show="isDesktop && collapsed && hoverIndex === {{ $index }}" x-transition.opacity
                            class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50">
                            {{ $link['label'] }}
                        </span>

                        <!-- Texto normal (menu expandido ou mobile) -->
                        <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity
                            class="ml-1 whitespace-nowrap">
                            {{ $link['label'] }}
                        </span>
                    </a>
                @endforeach
            </nav>

            <!-- Footer -->
            <footer class="p-4 border-t border-blue-800 text-sm text-center mt-auto">
                @auth
                    <!-- Footer completo (menu expandido / mobile) -->
                    <template x-if="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)">
                        <div>
                            <span class="block mb-2 text-blue-200 truncate">Olá, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-1.5 px-3 rounded text-sm flex items-center justify-center">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Sair
                                </button>
                            </form>
                            <p class="text-blue-300 text-xs mt-3">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                        </div>
                    </template>

                    <!-- Footer colapsado (apenas ícone) com popover de hover -->
                    <template x-if="isDesktop && collapsed">
                        <form method="POST" action="{{ route('admin.logout') }}" class="relative group">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 text-sm mx-auto block">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>

                            <!-- Texto “Sair” aparece ao passar o mouse -->
                            <span
                                class="absolute left-full top-1/2 -translate-y-1/2 ml-2 bg-red-600 text-white px-3 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                Sair
                            </span>
                        </form>
                    </template>
                @endauth
            </footer>
        </aside>

        <!-- Backdrop -->
        <div x-show="!isDesktop && sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-30" x-cloak>
        </div>

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col transition-all duration-300"
            :class="{
                'ml-60': isDesktop && !collapsed, // espaço para o menu expandido
                'ml-0': isDesktop && collapsed // espaço para o menu colapsado
            }">
            <header class="bg-white border-b px-4 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button @click="isDesktop ? (collapsed = !collapsed) : (sidebarOpen = !sidebarOpen)"
                        class="text-blue-900 hover:text-blue-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <span class="font-semibold text-blue-900 text-lg lg:hidden">{{ config('app.name') }}</span>
                </div>

                {{-- Pesquisa no lado direito --}}
                <div class="flex items-center space-x-1">
                    <form action="{{ route('admin.pesquisar') }}" method="GET" class="flex items-center space-x-2">
                        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                            <select name="tipo"
                                class="px-3 py-1.5 text-sm bg-white border-r border-gray-300 focus:outline-none focus:ring-blue-500">
                                <option value="cpf">CPF</option>
                                <option value="cns">Cartão SUS</option>
                                <option value="numero_atendimento">Nº Atendimento</option>
                            </select>

                            <input type="text" name="termo" placeholder="Digite aqui..."
                                class="px-3 py-1.5 text-sm focus:outline-none focus:ring-blue-500 flex-1" size="30"
                                required>

                            <button type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 flex items-center justify-center">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded flex items-center gap-1 text-sm">
                            <i class="fa fa-th text-xs fa-fw"></i>
                            <span class="hidden sm:inline">Menu rápido</span>
                            <i class="fa fa-caret-down ml-1"></i>
                        </button>

                        <!-- Menu dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-45 bg-white border border-gray-200 rounded shadow-lg z-50"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <a href="{{ route('admin.pacientes.formulario') }}"
                                class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 text-sm">
                                <i class="fa fa-plus text-xs fa-fw"></i> Novo Paciente
                            </a>
                            <a href="{{ route('admin.atendimentos.formulario') }}"
                                class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 text-sm">
                                <i class="fa fa-plus text-xs fa-fw"></i> Novo Atendimento
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 bg-white overflow-x-auto">
                <h1 class="text-lg font-semibold mb-6 text-blue-900 border-b pb-2">
                    {{ $title ?? 'Painel Administrativo' }}
                </h1>

                {{ $slot }}
            </main>
        </div>

        <a href="#top" title="Voltar ao Topo"><img src="{{ asset('images/up-arrow.png') }}" alt=""></a>
    </div>

    <script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>

    @livewireScripts

    @stack('scripts')

    <script type="text/javascript">
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        }

        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('a[href="#top"]').fadeIn().css('display', 'flex');
                } else {
                    $('a[href="#top"]').fadeOut();
                }
            });

            $('a[href="#top"]').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        });

        function maiuscula(z) {
            v = z.value.toUpperCase();
            z.value = v;
        }

        function minuscula(z) {
            v = z.value.toLowerCase();
            z.value = v;
        }
    </script>
</body>

</html>
