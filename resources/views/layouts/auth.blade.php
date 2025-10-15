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

        .auth-bg {
            background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
        }
    </style>
</head>

<body class="auth-bg min-h-screen flex items-center justify-center text-gray-800">

    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- Coluna Esquerda: informações -->
        <div class="bg-blue-900 text-white flex flex-col justify-center items-center p-10 space-y-6">
            <div class="text-center">
                <div class="bg-white text-blue-900 rounded-full w-20 h-20 flex items-center justify-center text-4xl shadow-lg mx-auto mb-4">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <h1 class="text-3xl font-bold tracking-wide">{{ config('app.name') }}</h1>
                <p class="text-blue-100 mt-2">Painel Administrativo</p>
            </div>

            <div class="border-t border-blue-700 w-20 my-4"></div>

            <p class="text-center text-blue-100 text-sm leading-relaxed max-w-sm">
                Sistema de gestão e organização de documentos públicos municipais.  
                Acesse, gerencie e mantenha o acervo digital de forma simples e segura.
            </p>

            <ul class="text-sm text-blue-100 space-y-2 mt-4">
                <li><i class="fa-solid fa-check mr-2 text-green-300"></i> Acesso seguro e autenticado</li>
                <li><i class="fa-solid fa-check mr-2 text-green-300"></i> Gerenciamento centralizado</li>
                <li><i class="fa-solid fa-check mr-2 text-green-300"></i> Backup e histórico automático</li>
            </ul>
        </div>

        <!-- Coluna Direita: conteúdo dinâmico -->
        <div class="flex flex-col justify-center items-center p-10 bg-white">
            <main class="w-full max-w-md">
                {{ $slot }}
            </main>

            <footer class="text-center text-gray-500 text-xs mt-8">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </footer>
        </div>
    </div>

    @livewireScripts
</body>

</html>
