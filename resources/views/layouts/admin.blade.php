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
                    $isDesktop = true; // exemplo, se você controla com Alpine
                @endphp

                <!-- Dashboard -->
                @php
                    $active = request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                @endphp
                <a href="{{ route('admin.dashboard') }}"
                    class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                    @mouseenter="hoverIndex = 'dashboard'" @mouseleave="hoverIndex = null">
                    <i class="fa-solid fa-gauge text-md min-w-[1.5rem] text-center"></i>

                    <span x-show="isDesktop && collapsed && hoverIndex === 'dashboard'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Dashboard</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Dashboard </span>
                </a>

                @if(auth()->user()->hasRole(['Recepcao', 'Marcacao', 'Gestor']))
                    <!-- Setores -->
                    @can('Listar Setor')
                    @php
                        $active = request()->routeIs('admin.setores*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp

                    <a href="{{ route('admin.setores.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'setores'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-building text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'setores'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Setores</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Setores </span>
                    </a>
                    @endif

                    <!-- Tipos de Procedimento -->
                    @can('Listar Tipo de Procedimento')
                    @php
                        $active = request()->routeIs('admin.tipos_procedimento*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.tipos_procedimento.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'tipos_procedimento'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-list-check text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'tipos_procedimento'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Tipos de Procedimento</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Tipos de Procedimento </span>
                    </a>
                    @endif

                    <!-- Procedimentos -->
                    @can('Listar Procedimento')
                    @php
                        $active = request()->routeIs('admin.procedimentos*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.procedimentos.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'procedimentos'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-stethoscope text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'procedimentos'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Procedimentos</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Procedimentos </span>
                    </a>
                    @endif

                    <!-- Estados -->
                    @can('Listar Estado')
                    @php
                        $active = request()->routeIs('admin.estados*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.estados.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'estados'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-map text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'estados'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Estados</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Estados </span>
                    </a>
                    @endif

                    <!-- Municípios -->
                    @can('Listar Municipio')
                    @php
                        $active = request()->routeIs('admin.municipios*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.municipios.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'municipios'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-city text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'municipios'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Municípios</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Municípios </span>
                    </a>
                    @endif

                    <!-- Equipes de Saúde -->
                    @can('Listar Equipe de Saude')
                    @php
                        $active = request()->routeIs('admin.equipes_saude*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.equipes_saude.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'equipes_saude'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-house-medical text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'equipes_saude'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Equipes de Saúde</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Equipes de Saúde </span>
                    </a>
                    @endif

                    <!-- Agentes de Saúde -->
                    @can('Listar Agente de Saude')
                    @php
                        $active = request()->routeIs('admin.agentes_saude*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.agentes_saude.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'agentes_saude'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-user-nurse text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'agentes_saude'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Agentes de Saúde</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Agentes de Saúde </span>
                    </a>
                    @endif

                    <!-- Pacientes -->
                    @can('Listar Paciente')
                    @php
                        $active = request()->routeIs('admin.pacientes*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.pacientes.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'pacientes'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-user-injured text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'pacientes'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Pacientes</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Pacientes </span>
                    </a>
                    @endif

                    <!-- Atendimentos -->
                    @can('Listar Atendimento')
                    @php
                        $active = request()->routeIs('admin.atendimentos*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.atendimentos.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'atendimentos'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-calendar-check text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'atendimentos'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Atendimentos</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Atendimentos </span>
                    </a>
                    @endif

                    <!-- Solicitações -->
                    @can('Listar Solicitacao')
                    @php
                        $active = request()->routeIs('admin.solicitacoes*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.solicitacoes.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'solicitacoes'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-file-medical text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'solicitacoes'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Solicitações</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Solicitações </span>
                    </a>
                    @endif

                    <!-- Movimentações -->
                    @can('Realizar Movimentacao')
                    @php
                        $active = request()->routeIs('admin.movimentacoes*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.movimentacoes.formulario') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'movimentacoes'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-right-left text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'movimentacoes'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Movimentações</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Movimentações </span>
                    </a>
                    @endif

                    <!-- Relatórios -->
                    @can('Gerenciar Relatorios')
                    @php
                        $active = request()->routeIs('admin.relatorios*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.relatorios') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'relatorios'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-pie-chart text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'relatorios'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Relatórios</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Relatórios </span>
                    </a>
                    @endif
                @endif

                @if(auth()->user()->hasRole('Administrador'))
                    <!-- Permissões -->
                    @can('Listar Permissao')
                    @php
                        $active = request()->routeIs('admin.permissoes*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.permissoes.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'permissoes'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-key text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'permissoes'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Permissões</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Permissões </span>
                    </a>
                    @endif

                    <!-- Perfis -->
                    @can('Listar Perfil')
                    @php
                        $active = request()->routeIs('admin.perfis*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.perfis.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'perfis'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-user-shield text-md min-w-[1.5rem] text-center"></i>
                        
                        <span x-show="isDesktop && collapsed && hoverIndex === 'perfis'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Perfis</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Perfis </span>
                    </a>
                    @endif

                    <!-- Usuários -->
                    @can('Listar Usuario')
                    @php
                        $active = request()->routeIs('admin.usuarios*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800';
                    @endphp
                    <a href="{{ route('admin.usuarios.listagem') }}"
                        class="relative flex items-center py-2 px-2 rounded transition text-sm {{ $active }}"
                        @mouseenter="hoverIndex = 'usuarios'" @mouseleave="hoverIndex = null">
                        <i class="fa-solid fa-users text-md min-w-[1.5rem] text-center"></i>

                        <span x-show="isDesktop && collapsed && hoverIndex === 'usuarios'" x-transition.opacity class="absolute text-sm left-full top-1/2 -translate-y-1/2 bg-blue-800 text-white px-2 py-2 rounded shadow-lg whitespace-nowrap z-50"> Usuários</span> <!-- Texto normal (menu expandido ou mobile) --> <span x-show="(!isDesktop && sidebarOpen) || (isDesktop && !collapsed)" x-transition.opacity class="ml-1 whitespace-nowrap"> Usuários </span>
                    </a>
                    @endif
                @endif
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
                                <option value="cartao_sus">Cartão SUS</option>
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
