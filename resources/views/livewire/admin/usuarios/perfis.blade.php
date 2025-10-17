<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    <div class="space-y-4">
        <form wire:submit="syncPerfis" id="perfis">
            <blockquote class="titulo-sessao-formulario mt-0">
                <p>Perfis para: {{ $usuario->name }}</p>
                <small>Relação dos Perfis Disponíveis</small>
            </blockquote>

            <div class="row ml-4 mb-2 mt-5">
                <div class="col-md-12">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        @foreach ($perfis as $perfil)
                            <div class="col-md-4">
                                <label class="custom-control custom-checkbox" for="perfil-{{ $perfil->id }}">
                                    <input id="perfil-{{ $perfil->id }}" class="custom-control-input" type="checkbox"
                                        wire:model="perfis_selecionados" value="{{ $perfil->id }}">
                                    <span class="custom-control-indicator"></span> {{ $perfil->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0 mt-5">
                <a href="{{ route('admin.usuarios.listagem') }}"
                    class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i>
                    Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm"><i
                    class="fa fa-save fa-fw"></i> Sincronizar {{ $usuario->name }}</button>
            </div>
        </form>        
    </div>
</div>
