<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3 text-center">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Nome do Paciente -->
        <div>
            <label class="block text-sm font-semibold mb-1">Nome do Paciente <span class="text-red-600 text-sm">*</span></label>
            <input type="text" wire:model="paciente_nome" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('paciente_nome') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Cartão do SUS -->
            <div>
                <label class="block text-sm font-semibold mb-1">Cartão do SUS <span class="text-red-600 text-sm">*</span></label>
                <input type="text" wire:model="paciente_cns" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('paciente_cns') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- CPF nº -->
            <div>
                <label class="block text-sm font-semibold mb-1">CPF nº <span class="text-red-600 text-sm">*</span></label>
                <input type="text" wire:model="paciente_cpf" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('paciente_cpf') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>        

        <!-- Agente de Saúde -->
        <div>
            <label class="block text-sm font-semibold mb-1">Agente de Saúde</label>
            <select wire:model="paciente_agente_saude_id" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Selecionar --</option>
                @foreach($agentes_saude as $agente_saude)
                    <option value="{{ $agente_saude->agente_saude_id }}">{{ $agente_saude->agente_saude_nome }}</option>
                @endforeach
            </select>
            @error('paciente_agente_saude_id') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Sexo -->
            <div>
                <label class="block text-sm font-semibold mb-1">Sexo <span class="text-red-600 text-sm">*</span></label>
                <select wire:model="paciente_sexo" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Selecionar --</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Masculino">Masculino</option>
                </select>
                @error('paciente_sexo') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Data de Nascimento -->
            <div>
                <label class="block text-sm font-semibold mb-1">Data de Nascimento <span class="text-red-600 text-sm">*</span></label>
                <input type="date" wire:model="paciente_data_nascimento" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('paciente_data_nascimento') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Nome da Mãe -->
        <div>
            <label class="block text-sm font-semibold mb-1">Nome da Mãe <span class="text-red-600 text-sm">*</span></label>
            <input type="text" wire:model="paciente_nome_mae" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('paciente_nome_mae') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <!-- Endereço -->
        <div>
            <label class="block text-sm font-semibold mb-1">Endereço <span class="text-red-600 text-sm">*</span></label>
            <input type="text" wire:model="paciente_endereco" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('paciente_endereco') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Contato -->
            <div>
                <label class="block text-sm font-semibold mb-1">Contato <span class="text-red-600 text-sm">*</span></label>
                <input type="text" wire:model="paciente_contato" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('paciente_contato') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold mb-1">Status <span class="text-red-600 text-sm">*</span></label>
                <select wire:model="paciente_status" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
                @error('paciente_status') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0">
            <a href="{{ route('admin.pacientes.listagem') }}" class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i> Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm"><i class="fa fa-save fa-fw"></i> Salvar Registro</button>
        </div>
    </form>
</div>
