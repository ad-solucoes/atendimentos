<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Atendimentos</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Paciente</th>
                <th class="p-2 text-left">Data</th>
                <th class="p-2 text-left">Descrição</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $item)
                <tr class="border-b">
                    <td class="p-2">{{ $item->paciente->nome ?? '-' }}</td>
                    <td class="p-2">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-2">{{ $item->descricao ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
