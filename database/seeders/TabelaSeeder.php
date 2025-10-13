<?php

declare(strict_types = 1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SolicitacaoMovimentacao;

use App\Models\TipoProcedimento;
use App\Models\Procedimento;
use App\Models\Setor;
use App\Models\EquipeSaude;
use App\Models\AgenteSaude;
use App\Models\Paciente;
use App\Models\Atendimento;
use App\Models\Solicitacao;
use Faker\Factory as Faker;

class TabelaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        /**
         * TABELA: tipo_procedimentos
         */
        $dataTipoProcedimento = [
            ['tipo_procedimento_nome' => 'Consulta'],
            ['tipo_procedimento_nome' => 'Exame'],
            ['tipo_procedimento_nome' => 'Outros']
        ];
        TipoProcedimento::insert($dataTipoProcedimento);

        /**
         * TABELA: procedimentos
         */
        $dataProcedimento = [
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta 01'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta 02'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta 03'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Exame 01'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Exame 02'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Exame 03'],
            ['procedimento_tipo_id' => 3, 'procedimento_nome' => 'Outro 01'],
            ['procedimento_tipo_id' => 3, 'procedimento_nome' => 'Outro 02'],
            ['procedimento_tipo_id' => 3, 'procedimento_nome' => 'Outro 03'],
        ];
        Procedimento::insert($dataProcedimento);

        /**
         * TABELA: setores
         */
        $dataSetor = [
            ['setor_nome' => 'Recepção'],
            ['setor_nome' => 'Marcação'],
            ['setor_nome' => 'Marcação Externa'],
        ];
        Setor::insert($dataSetor);

        User::factory()->create([
            'name'     => 'User Admin',
            'email'    => 'admin@email.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name'            => 'User Recepção',
            'email'           => 'recepcao@email.com',
            'setor_id'           => 1,
        ]);

        User::factory()->create([
            'name'            => 'User Marcação',
            'email'           => 'marcacao@email.com',
            'setor_id'           => 2
        ]);

        /**
         * TABELA: equipes de saúde
         */
        $dataEquipeSaude = [
            ['equipe_saude_nome' => 'UBS São Sebastião'],
            ['equipe_saude_nome' => 'UBS Marinete Baltazar'],
            ['equipe_saude_nome' => 'UBS Santa Luzia'],
            ['equipe_saude_nome' => 'UBS Dr. Meroveu'],
            ['equipe_saude_nome' => 'UBS Aurora Nazaret'],
            ['equipe_saude_nome' => 'UBS Normando Barbosa'],
        ];
        EquipeSaude::insert($dataEquipeSaude);

        /**
         * TABELA: agentes de saúde
         */
        $dataAgenteSaude = [
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Claudeane França de Lima'],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Eliene Maria Silva dos Santos'],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Daniela Silva de Souza'],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Dulce Maria dos Santos'],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Eduardo de Lima Vasco'],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Jaqueline Feijó da Silva'],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Adriana Maria da Silva'],
        ];
        AgenteSaude::insert($dataAgenteSaude);

        /**
         * TABELA: pacientes
         */
        $dataPaciente = [];
        for ($i = 1; $i <= 10; $i++) {
            $dataPaciente[] = [
                'paciente_agente_saude_id' => rand(1, 6),
                'paciente_cpf' => $faker->cpf(false),
                'paciente_nome' => $faker->name(),
                'paciente_sexo' => $faker->randomElement(['Masculino', 'Feminino']),
                'paciente_data_nascimento' => $faker->date(),
                'paciente_nome_mae' => $faker->name('female'),
                'paciente_endereco' => $faker->address(),
                'paciente_contato' => '(82) ' . rand(90000, 99999) . '-' . rand(1000, 9999),
                'paciente_cns' => $faker->numerify('###############')
            ];
        }
        Paciente::insert($dataPaciente);

        /**
         * TABELA: atendimentos
         * Número gerado no formato AAAAMMDDNNN
         */
        $dataAtendimento = [];
        $dataAtual = date('Ymd');
        for ($i = 1; $i <= 10; $i++) {
            $sequencia = formatoId($i, 3);
            $numeroAtendimento = $dataAtual . $sequencia;
            $dataAtendimento[] = [
                'atendimento_paciente_id' => $i,
                'atendimento_numero' => $numeroAtendimento,
                'atendimento_data' => now(),
            ];
        }
        Atendimento::insert($dataAtendimento);

        /**
         * TABELA: solicitações
         * Inclui enum solicitacao_status
         */
        $statusList = ['aguardando', 'agendado', 'marcado', 'entregue', 'cancelado'];

        $dataSolicitacao = [];

        for ($i = 1; $i <= 10; $i++) {
            $procedimentoId = rand(1, 9); // ajustável conforme a quantidade real de procedimentos

            $dataSolicitacao[] = [
                'solicitacao_atendimento_id'       => $i,
                'solicitacao_procedimento_id'      => $procedimentoId,
                'solicitacao_localizacao_atual_id' => null, // será atualizado conforme a movimentação
                'solicitacao_numero'               => $faker->unique()->numerify('S######'),
                'solicitacao_data'                 => $faker->dateTimeBetween('-30 days', 'now'),
                'solicitacao_status'               => $faker->randomElement($statusList),
                'created_user_id'                  => 1,
                'updated_user_id'                  => 1,
                'created_at'                       => now(),
                'updated_at'                       => now(),
            ];
        }

        Solicitacao::insert($dataSolicitacao);

        $solicitacoes = Solicitacao::all();

        foreach ($solicitacoes as $solicitacao) {
            $movimentos = [];

            // Movimentação inicial: recepção -> marcação (encaminhamento)
            $movimentos[] = [
                'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                'movimentacao_usuario_id'      => 1, // recepcionista
                'movimentacao_destino_id'      => null, // setor marcação pode ser preenchido
                'movimentacao_tipo'            => 'encaminhamento',
                'movimentacao_entregue_para'   => null,
                'movimentacao_observacao'      => 'Encaminhada para marcação',
                'movimentacao_data'            => $faker->dateTimeBetween($solicitacao->solicitacao_data, 'now'),
            ];

            // Se o status for 'marcada', gerar retorno para recepção
            if ($solicitacao->solicitacao_status === 'marcada') {
                $movimentos[] = [
                    'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                    'movimentacao_usuario_id'      => 2, // setor de marcação
                    'movimentacao_destino_id'      => null, // retorno para recepção
                    'movimentacao_tipo'            => 'retorno',
                    'movimentacao_entregue_para'   => null,
                    'movimentacao_observacao'      => 'Retorno da marcação',
                    'movimentacao_data'            => $faker->dateTimeBetween($solicitacao->solicitacao_data, 'now'),
                ];

                // Movimentação de entrega ao paciente/ACS/Equipe
                $entreguePara = $faker->randomElement(['paciente', 'agente_saude', 'equipe_saude']);
                $movimentos[] = [
                    'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                    'movimentacao_usuario_id'      => 1, // recepcionista
                    'movimentacao_destino_id'      => null,
                    'movimentacao_tipo'            => 'entrega',
                    'movimentacao_entregue_para'   => $entreguePara,
                    'movimentacao_observacao'      => 'Entrega realizada',
                    'movimentacao_data'            => now(),
                ];
            }

            // Inserir todas movimentações geradas
            SolicitacaoMovimentacao::insert($movimentos);
        }
    }
}
