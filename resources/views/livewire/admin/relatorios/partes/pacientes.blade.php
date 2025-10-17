<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Pacientes</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nome</th>
                <th class="p-2 text-left">CPF</th>
                <th class="p-2 text-left">Agente de Saúde</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $item)
                <tr class="border-b">
                    <td class="p-2">{{ $item->paciente_nome }}</td>
                    <td class="p-2">{{ $item->paciente_cpf }}</td>
                    <td class="p-2">{{ $item->agente_saude->agente_saude_nome ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
