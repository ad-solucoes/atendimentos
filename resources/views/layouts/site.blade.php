<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Acervo Municipal' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans antialiased">

    <!-- Header -->
    <header x-data="{ open: false }" class="bg-blue-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('site.inicio') }}" class="text-2xl font-bold flex items-center space-x-2">
                📜 <span>Acervo Municipal</span>
            </a>

            <!-- Menu desktop -->
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('site.inicio') }}" class="hover:underline">Início</a>
                <a href="{{ route('site.buscar') }}" class="hover:underline">Buscar Documentos</a>
                <a href="{{ route('site.sobre') }}" class="hover:underline">Sobre</a>
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin</a>
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
        <div x-show="open" x-transition class="md:hidden bg-blue-600 text-white px-6 pb-4 space-y-3">
            <a href="{{ route('site.inicio') }}" class="block py-1 border-b border-blue-500">Início</a>
            <a href="{{ route('site.buscar') }}" class="block py-1 border-b border-blue-500">Buscar Documentos</a>
            <a href="{{ route('site.sobre') }}" class="block py-1 border-b border-blue-500">Sobre</a>
            <a href="{{ route('admin.dashboard') }}" class="block py-1">Admin</a>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-6 py-10">
        {{ $slot }}
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-white text-center py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-sm md:text-base">
                &copy; {{ date('Y') }} <strong>Acervo Municipal de Documentos Públicos</strong>.<br class="md:hidden">
                Projeto sem fins lucrativos — Desenvolvido com ❤️ para o município.
            </p>
            <p class="text-xs text-gray-400 mt-2">Versão 1.0.0 | Laravel 12 + Livewire 3</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
