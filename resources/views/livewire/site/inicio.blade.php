<div title="Início">
    <section class="text-center py-10">
        <h1 class="text-4xl font-bold text-blue-700 mb-4">📜 Acervo Municipal de Documentos Públicos</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-6">
            Este sistema foi criado como uma iniciativa sem fins lucrativos para facilitar o acesso da população
            aos documentos oficiais do município. Aqui você pode buscar leis, decretos, portarias, atas e outros
            registros públicos de forma rápida e organizada.
        </p>

        <a href="{{ route('site.buscar') }}" 
           class="bg-blue-700 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-800 transition">
           🔍 Buscar Documentos
        </a>
    </section>

    <section class="mt-16 grid md:grid-cols-3 gap-6 text-center">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg">
            <h2 class="text-xl font-semibold text-blue-700 mb-2">Transparência</h2>
            <p class="text-gray-600">Acesso livre aos documentos públicos do município de forma organizada e atualizada.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg">
            <h2 class="text-xl font-semibold text-blue-700 mb-2">Histórico</h2>
            <p class="text-gray-600">Preservamos o histórico das publicações oficiais em um só lugar.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg">
            <h2 class="text-xl font-semibold text-blue-700 mb-2">Utilidade Pública</h2>
            <p class="text-gray-600">Facilitando a pesquisa de cidadãos, servidores e pesquisadores.</p>
        </div>
    </section>
</div>
