<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body form-box">
                    <div class="row" style="margin-bottom: -5px;margin-top: 0px">
                        <div class="col-md-12 text-center">
                            <h4 class="text-muted">Adicionar atendimento para o paciente selecionado</h4>
                            <a href="#" class="btn btn-primary btn-lg"><i
                                    class="btn-label-icon left fa fa-plus"></i> Gerar Atendimento para este
                                paciente</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin-top: 10px">
            <blockquote class="pull-right" style="border-color: #d1d1d1">
                @if (count($paciente->atendimentos) == 0)
                    <p>Não existe atendimento registrado para este paciente:</p>
                @else
                    <p>Existe(m) atendimento(s) já registrado(s) para este paciente:</p>
                @endif

                <h2 style="margin:0">{{ count($paciente->atendimentos) }}</h2>
                <small>Total de <cite title="Source Title">atendimentos</cite> realizados</small>
            </blockquote>
        </div>
    </div>

    <blockquote class="titulo-sessao-formulario">
        <p>Informações do Paciente</p>
        <small>Informações básicas</small>
    </blockquote>

    <div class="row" style="margin-bottom: 15px;margin-top: -10px">
        <div class="col-md-9 col-md-offset-1">
            <dl class="dl-horizontal area-visualizando">
                <dt>Paciente ID:</dt>
                <dd>#{{ formatoId($paciente->paciente_id, 2) }}</dd>
                <dt>Nome do Paciente:</dt>
                <dd>{{ $paciente->paciente_nome }}</dd>
                <dt>Cartão do SUS:</dt>
                <dd>{{ $paciente->paciente_cartao_sus }}</dd>
                <dt>Equipe de Saúde:</dt>
                <dd>{{ $paciente->agente_saude->equipe_saude->equipe_saude_nome }}</dd>
                <dt>Agente de Saúde:</dt>
                <dd>{{ $paciente->agente_saude?->agente_saude_nome }}</dd>
                <dt>Data de Nascimento:</dt>
                <dd>{{ $paciente->paciente_data_nascimento?->format('d/m/Y') }}</dd>
                <dt>Idade Atual:</dt>
                <dd>{!! $paciente->paciente_data_nascimento != ''
                    ? $paciente->paciente_idade . ' anos'
                    : '&nbsp;' !!}</dd>
                <dt>Nome da Mãe:</dt>
                <dd>{{ $paciente->paciente_nome_mae }}</dd>
                <dt>Sexo:</dt>
                <dd>
                    @if ($paciente->paciente_sexo)
                        @if ($paciente->paciente_sexo == 1)
                            Feminino
                        @else
                            Masculino
                        @endif
                    @endif
                </dd>
                <dt>Status:</dt>
                <dd>
                    @if ($paciente->paciente_status)
                        <span
                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                    @endif
                </dd>
                <dt>&nbsp;</dt>
                <dd class="m-t-1">
                    @can('Editar Paciente')
                        <a class="btn btn-warning btn-sm" href="#"><i
                                class="btn-label-icon right fa fa-pencil"></i> Editar Paciente</a>
                    @endcan
                </dd>
            </dl>
        </div>
    </div>

    <blockquote class="titulo-sessao-formulario">
        <p>Documentações</p>
        <small>Informações básicas</small>
    </blockquote>

    <div class="row" style="margin-bottom: 15px;margin-top: -10px">
        <div class="col-md-9 col-md-offset-1">
            <dl class="dl-horizontal area-visualizando">
                <dt>CPF Número:</dt>
                <dd>{!! $paciente->paciente_cpf != '' ? $paciente->paciente_cpf : '&nbsp;' !!}</dd>
            </dl>
        </div>
    </div>

    <blockquote class="titulo-sessao-formulario">
        <p>Endereço</p>
        <small>Informações básicas</small>
    </blockquote>

    <div class="row" style="margin-bottom: 15px;margin-top: -10px">
        <div class="col-md-9 col-md-offset-1">
            <dl class="dl-horizontal area-visualizando">
                <dt>Endereço:</dt>
                <dd>{!! $paciente->paciente_endereco != '' ? $paciente->paciente_endereco : '&nbsp;' !!}</dd>
                <dt>Número:</dt>
                <dd>{!! $paciente->paciente_numero != '' ? $paciente->paciente_numero : '&nbsp;' !!}</dd>
                <dt>Bairro:</dt>
                <dd>{!! $paciente->paciente_bairro != '' ? $paciente->paciente_bairro : '&nbsp;' !!}</dd>
                <dt>Complemento:</dt>
                <dd>{!! $paciente->paciente_complemento != '' ? $paciente->paciente_complemento : '&nbsp;' !!}</dd>
                <dt>Estado:</dt>
                <dd>{!! $paciente->paciente_municipio_id != '' ? $paciente->municipio->estado->estado_uf : '&nbsp;' !!}</dd>
                <dt>Município:</dt>
                <dd>{!! $paciente->paciente_municipio_id != '' ? $paciente->municipio->municipio_nome : '&nbsp;' !!}</dd>
            </dl>
        </div>
    </div>

    <blockquote class="titulo-sessao-formulario">
        <p>Contato</p>
        <small>Informações básicas</small>
    </blockquote>

    <div class="row" style="margin-bottom: 15px;margin-top: -10px">
        <div class="col-md-9 col-md-offset-1">
            <dl class="dl-horizontal area-visualizando">
                <dt>contato 01:</dt>
                <dd>{!! $paciente->paciente_contato_01 != '' ? $paciente->paciente_contato_01 : '&nbsp;' !!}</dd>
                <dt>contato 02:</dt>
                <dd>{!! $paciente->paciente_contato_02 != '' ? $paciente->paciente_contato_02 : '&nbsp;' !!}</dd>
                <dt>E-mail:</dt>
                <dd>{!! $paciente->paciente_email != '' ? $paciente->paciente_email : '&nbsp;' !!}</dd>
            </dl>
        </div>
    </div>

    <blockquote class="titulo-sessao-formulario">
        <p>Área de Impressões</p>
        <small>Relação de itens para impressão</small>
    </blockquote>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-9 col-md-offset-1">
            <ul class="list-group area-impressos">
                @can('Ficha Paciente')
                    <li class="list-group-item">
                        <a href="{{ route('movimentacoes.pacientes.ficha', ['paciente' => $paciente->paciente_id]) }}" target="_blank">
                            <h4 class="m-b-1"><i class="fa fa-print fa-fw"></i> Ficha de paciente</h4>
                        </a>
                        <small id="passwordHelpBlock" class="form-text text-muted">Páginas: 1 | Formulário:
                            1/1 | Orientação: Retrato</small>
                    </li>
                @endcan
                @can('Historico Paciente')
                    <li class="list-group-item">
                        <a href="{{ route('movimentacoes.pacientes.historico', ['paciente' => $paciente->paciente_id]) }}" target="_blank">
                            <h4 class="m-b-1"><i class="fa fa-print fa-fw"></i> Histórico do Paciente</h4>
                        </a>
                        <small id="passwordHelpBlock" class="form-text text-muted">Páginas: indefinida |
                            Formulário: ?/? | Orientação: Retrato</small>
                    </li>
                @endcan
            </ul>
        </div>
    </div>

    {{-- Header com ações --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
        <h2 class="text-lg font-semibold text-gray-700">Detalhes do Paciente</h2>

        <div class="flex items-center space-x-2">
            <span class="text-gray-600 text-sm">
                Total de Atendimentos:
                <strong>{{ $paciente->atendimentos->count() }}</strong>
            </span>

            <a href="{{ route('admin.atendimentos.formulario', ['paciente_id' => $paciente->paciente_id]) }}"
                class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded shadow text-sm flex items-center justify-center gap-2 transition">
                <i class="fa fa-plus"></i> Novo Atendimento
            </a>

            <a href="{{ route('admin.pacientes.impressao', ['id' => $paciente->paciente_id]) }}" target="_blank"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded shadow text-sm flex items-center justify-center gap-2 transition">
                <i class="fa fa-file-pdf"></i> Gerar PDF
            </a>
        </div>
    </div>

    {{-- Informações básicas do paciente --}}
    <div class="space-y-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <h3 class="text-gray-400 text-sm font-medium">#ID</h3>
                <p class="text-gray-800 font-semibold">{{ formatoId($paciente->paciente_id, 3) }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Nome</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_nome }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">CPF</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_cpf }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Cartão SUS</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_cartao_sus }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Data de Nascimento</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_data_nascimento->format('d/m/Y') }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Status</h3>
                @if ($paciente->paciente_status)
                    <span
                        class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                @endif
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Nome da Mãe</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_nome_mae }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Telefone</h3>
                <p class="text-gray-800 font-semibold">{{ $paciente->paciente_telefone ?? 'Não informado' }}</p>
            </div>
        </div>

        {{-- Ficha de Cadastro --}}
        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-gray-600 font-semibold mb-2">Ficha de Cadastro</h3>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>Endereço:</strong> {{ $paciente->paciente_endereco ?? 'Não informado' }}</p>
                <p><strong>Bairro:</strong> {{ $paciente->paciente_bairro ?? 'Não informado' }}</p>
                <p><strong>Cidade:</strong> {{ $paciente->paciente_cidade ?? 'Não informado' }}</p>
                <p><strong>CEP:</strong> {{ $paciente->paciente_cep ?? 'Não informado' }}</p>
                <p><strong>E-mail:</strong> {{ $paciente->paciente_email ?? 'Não informado' }}</p>
            </div>
        </div>

        {{-- Histórico de atendimentos --}}
        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-gray-600 font-semibold mb-2 flex justify-between items-center">
                Histórico de Atendimentos
                <span class="text-sm text-gray-500">{{ $paciente->atendimentos->count() }} registro(s)</span>
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 border-b">#ID</th>
                            <th class="px-3 py-2 border-b">Data</th>
                            <th class="px-3 py-2 border-b">Prioridade</th>
                            <th class="px-3 py-2 border-b">Profissional</th>
                            <th class="px-3 py-2 border-b">Status</th>
                            <th class="px-3 py-2 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paciente->atendimentos as $atendimento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">{{ formatoId($atendimento->atendimento_id, 3) }}</td>
                                <td class="px-3 py-2">{{ $atendimento->atendimento_data->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">{{ $atendimento->atendimento_prioridade }}</td>
                                <td class="px-3 py-2">{{ $atendimento->profissional->profissional_nome ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @if ($atendimento->solicitacoes)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Concluído</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.atendimentos.detalhes', $atendimento->atendimento_id) }}"
                                        class="text-blue-600 hover:underline text-sm">Exibir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                    Nenhum atendimento registrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
