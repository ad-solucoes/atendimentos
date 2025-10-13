<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autenticação - Acervo Municipal' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col items-center justify-center min-h-screen text-gray-800">

    <!-- Cabeçalho / Logo -->
    <header class="text-center mb-8">
        <a href="/" class="flex flex-col items-center space-y-2">
            <div class="text-5xl">📜</div>
            <h1 class="text-2xl font-bold text-blue-800 tracking-wide">Acervo Municipal</h1>
            <p class="text-sm text-blue-600">Painel Administrativo</p>
        </a>
    </header>

    <!-- Área do formulário -->
    <main class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 mx-4">
        {{ $slot }}
    </main>

    <!-- Rodapé -->
    <footer class="text-center text-gray-500 text-sm mt-8 mb-4">
        <p>&copy; {{ date('Y') }} Acervo Municipal. Todos os direitos reservados.</p>
    </footer>

    @livewireScripts
</body>
</html>
