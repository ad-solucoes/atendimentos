<div>
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar por nome..." class="border rounded px-3 py-2 w-1/2" />
        <a href="{{ route('admin.usuarios.formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Novo Usuário</a>
    </div>

    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full bg-white border rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100 text-left text-sm uppercase">
                <th class="px-4 py-2">Nome</th>
                <th class="px-4 py-2">E-mail</th>
                <th class="px-4 py-2">Admin</th>
                <th class="px-4 py-2 text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $usuario->name }}</td>
                    <td class="px-4 py-2">{{ $usuario->email }}</td>
                    <td class="px-4 py-2">{{ $usuario->isAdmin() ? 'Sim' : 'Não' }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.usuarios.formulario', $usuario->id) }}" class="text-yellow-600 hover:underline">Editar</a>
                        <button wire:click="confirmDelete({{ $usuario->id }})" class="text-red-600 hover:underline">Excluir</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center py-4">Nenhuma usuario cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $usuarios->links() }}</div>

    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-lg font-semibold mb-3">Confirmar exclusão</h2>
                <p class="mb-4">Tem certeza que deseja excluir esta usuario?</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 border rounded">Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded">Excluir</button>
                </div>
            </div>
        </div>
    @endif
</div>
