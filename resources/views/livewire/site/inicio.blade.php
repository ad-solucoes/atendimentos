<div title="Início">
    <!-- Hero Section -->
    <section class="flex flex-col md:flex-row items-center justify-between py-14 md:py-20 max-w-6xl mx-auto px-6 gap-10">
        <div class="md:w-1/2 text-center md:text-left">
            <h1 class="text-5xl font-extrabold text-blue-800 leading-tight mb-4">
                <i class="fa-solid fa-heart-pulse text-green-500 mr-2"></i>
                Saúde Digital Municipal
            </h1>
            <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                A nova plataforma da <strong>Secretaria Municipal de Saúde</strong> desenvolvida para oferecer 
                ao cidadão transparência e agilidade no acompanhamento de seus atendimentos e solicitações de saúde.
            </p>

            <a href="{{ route('site.consultar') }}" 
               class="inline-flex items-center bg-blue-700 text-white px-6 py-3 rounded-full font-semibold shadow hover:bg-blue-800 transition">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Consultar Atendimento
            </a>
        </div>

        <div class="md:w-1/2 flex justify-center">
            <img src="{{ asset('images/fundo-site.jpg') }}" 
                 alt="Ilustração Saúde Digital"
                 class="">
        </div>
    </section>
</div>
