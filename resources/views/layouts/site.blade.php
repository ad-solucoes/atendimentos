<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'e-Saúde Municipal' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin"
        rel="stylesheet" type="text/css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html {
            font-size: 90% !important;
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans antialiased">

    <!-- Header -->
    <header x-data="{ open: false }" class="bg-blue-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('site.inicio') }}" class="text-2xl font-bold flex items-center space-x-2">
                <i class="fa-solid fa-heart-pulse text-red-400"></i>
                <span>e-Saúde Municipal</span>
            </a>

            <!-- Menu desktop -->
            <nav class="hidden md:flex space-x-6 text-sm font-medium">
                <a href="{{ route('site.inicio') }}" class="hover:underline"><i class="fa-solid fa-home fa-fw"></i> Início</a>
                <a href="{{ route('site.consultar') }}" class="hover:underline"><i class="fa-solid fa-search fa-fw"></i> Consultar Atendimento</a>
                <a href="{{ route('site.sobre') }}" class="hover:underline"><i class="fa-solid fa-th fa-fw"></i> Sobre o Projeto</a>
            </nav>

            <!-- Botão mobile -->
            <button @click="open = !open" class="md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu mobile -->
        <div x-show="open" x-transition class="md:hidden bg-blue-700 text-white px-6 pb-4 space-y-3">
            <a href="{{ route('site.inicio') }}" class="block py-1 border-b border-blue-600">Início</a>
            <a href="{{ route('site.consultar') }}" class="block py-1 border-b border-blue-600">Consultar Atendimento</a>
            <a href="{{ route('site.sobre') }}" class="block py-1">Sobre</a>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="flex-grow max-w-6xl mx-auto w-full px-6 py-10">
        {{ $slot }}
    </main>

    <!-- Rodapé -->
    <footer class="bg-blue-900 text-white text-center py-6 mt-auto">
        <div class="max-w-6xl mx-auto px-6">
            <p class="text-sm md:text-base">
                &copy; {{ date('Y') }} <strong>Secretaria Municipal de Saúde</strong> — Todos os direitos reservados.
            </p>
            <p class="text-xs text-blue-300 mt-2">Sistema e-Saúde Municipal | Versão 1.0</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
