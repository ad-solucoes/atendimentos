<div class="bg-white border border-gray-200 rounded-xl shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="p-2 text-center"><input type="checkbox" wire:model.live="selecionadas_all"></th>
                <th class="p-2 text-left">Nº da Solicitação</th>
                <th class="p-2 text-left">Paciente</th>
                <th class="p-2 text-left">Procedimento</th>
                <th class="p-2 text-left">Setor Atual</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse($solicitacoes as $s)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-2 text-center">
                        <input type="checkbox" wire:model.live="selecionadas" value="{{ $s->solicitacao_id }}">
                    </td>
                    <td class="p-2">{{ $s->solicitacao_numero }}</td>
                    <td class="p-2">{{ $s->atendimento->paciente->paciente_cpf ?? '-' }} | {{ $s->atendimento->paciente->paciente_nome ?? '-' }}</td>
                    <td class="p-2">{{ $s->procedimento->procedimento_nome ?? '-' }}</td>
                    <td class="p-2">{{ $s->localizacao_atual->setor_nome ?? '-' }}</td>
                    <td class="p-2 capitalize">{{ $s->solicitacao_status }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($s->solicitacao_data)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 p-4">Nenhuma solicitação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="p-3">
        {{ $solicitacoes->links() }}
    </div>
</div>
