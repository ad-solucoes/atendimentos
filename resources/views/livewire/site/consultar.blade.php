<div title="Consultar Atendimento" class="max-w-2xl mx-auto bg-white shadow-lg rounded-2xl p-8 border border-blue-100">

    <h1 class="text-3xl font-bold text-blue-800 text-center mb-6">
        <i class="fa-solid fa-stethoscope mr-2 text-green-500"></i> Consultar Atendimento
    </h1>

    <p class="text-gray-600 text-center mb-8">
        Insira as informações abaixo para visualizar o andamento do seu atendimento na Secretaria Municipal de Saúde.
    </p>

    <!-- Mensagens globais -->
    @if (session()->has('erro'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('erro') }}
        </div>
    @elseif (session()->has('aviso'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-4">
            <i class="fa-solid fa-circle-info mr-1"></i> {{ session('aviso') }}
        </div>
    @endif

    <form wire:submit.prevent="buscarAtendimento" class="space-y-5">

        <!-- Número do Atendimento -->
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-hashtag text-blue-600 mr-1"></i> Número do Atendimento
            </label>
            <input type="text" wire:model.defer="numero_atendimento"
                class="w-full px-4 py-3 border-2 rounded-xl text-lg placeholder-gray-400 
                @error('numero_atendimento') border-red-400 focus:ring-red-500 @else border-blue-200 focus:ring-blue-500 @enderror"
                placeholder="Ex: 2025-001234" required>
            @error('numero_atendimento')
                <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        <!-- CPF -->
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-id-card text-blue-600 mr-1"></i> CPF
            </label>
            <input type="text" wire:model.defer="cpf"
                class="w-full px-4 py-3 border-2 rounded-xl text-lg placeholder-gray-400 
                @error('cpf') border-red-400 focus:ring-red-500 @else border-blue-200 focus:ring-blue-500 @enderror"
                placeholder="000.000.000-00" required>
            @error('cpf')
                <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        <!-- Ano de Nascimento -->
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-calendar text-blue-600 mr-1"></i> Ano de Nascimento
            </label>
            <input type="number" wire:model.defer="ano_nascimento"
                class="w-full px-4 py-3 border-2 rounded-xl text-lg placeholder-gray-400 
                @error('ano_nascimento') border-red-400 focus:ring-red-500 @else border-blue-200 focus:ring-blue-500 @enderror"
                placeholder="Ex: 1985" required>
            @error('ano_nascimento')
                <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-xl font-semibold text-lg shadow transition">
            <i class="fa-solid fa-magnifying-glass mr-2"></i> Consultar
        </button>
    </form>

    <!-- Resultado -->
    @if($resultado)
        <div class="mt-10 border-t border-gray-200 pt-6">
            <h2 class="text-2xl font-bold text-blue-700 mb-4">Resultado da Consulta</h2>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                <p><strong>👤 Paciente:</strong> {{ $resultado->paciente->paciente_nome }}</p>
                <p><strong>📅 Data do Atendimento:</strong> {{ $resultado->atendimento_data->format('d/m/Y') }}</p>
                <p><strong>🩺 Número:</strong> {{ $resultado->atendimento_numero }}</p>
            </div>

            <h3 class="text-lg font-semibold text-gray-700 mb-3">Solicitações vinculadas:</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border border-gray-200 rounded-xl">
                    <thead class="bg-blue-100 text-blue-900">
                        <tr>
                            <th class="px-4 py-2">Procedimento</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($resultado->solicitacoes as $sol)
                            @php
                                $status = strtolower($sol->solicitacao_status);
                                $cores = [
                                    'aguardando' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                                    'marcado' => 'bg-blue-100 text-blue-800 border border-blue-300',
                                    'agendado' => 'bg-indigo-100 text-indigo-800 border border-indigo-300',
                                    'entregue' => 'bg-green-100 text-green-800 border border-green-300',
                                    'cancelado' => 'bg-red-100 text-red-800 border border-red-300',
                                ];
                                $corStatus = $cores[$status] ?? 'bg-gray-100 text-gray-700 border border-gray-300';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $sol->procedimento->procedimento_nome }}</td>
                                <td class="px-4 py-2">{{ $sol->procedimento->tipo_procedimento->tipo_procedimento_nome }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $corStatus }}">
                                        {{ ucfirst($sol->solicitacao_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
