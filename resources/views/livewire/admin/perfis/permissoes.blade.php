<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    <div class="space-y-4">
        
        <form wire:submit="syncPermissoes" id="permissoes">
            <blockquote class="titulo-sessao-formulario m-t-0">
                <p>Permissões para: {{ $perfil->name }}</p>
                <small>Relação dos Permissões Disponíveis</small>
            </blockquote>

            <div class="row m-l-4 m-b-2">
                <div class="col-md-12">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        @foreach ($permissoes as $permissao)
                            <div class="col-md-4">
                                <label class="custom-control custom-checkbox"
                                    for="permissao-{{ $permissao->id }}">
                                    <input id="permissao-{{ $permissao->id }}" class="custom-control-input"
                                        type="checkbox" wire:model="permissoes_selecionados"
                                        value="{{ $permissao->id }}">
                                    <span class="custom-control-indicator"></span> {{ $permissao->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0 mt-5">
                <a href="{{ route('admin.perfis.listagem') }}"
                    class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i>
                    Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm"><i
                    class="fa fa-save fa-fw"></i> Sincronizar {{ $perfil->name }}</button>
            </div>
        </form>        
    </div>
</div>
