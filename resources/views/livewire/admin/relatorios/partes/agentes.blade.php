<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Agentes de Saúde</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nome</th>
                <th class="p-2 text-left">Pacientes Vinculados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $item)
                <tr class="border-b">
                    <td class="p-2">{{ $item->agente_saude_nome }}</td>
                    <td class="p-2">{{ $item->pacientes_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
