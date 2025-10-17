<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\AgenteSaude;
use App\Models\Atendimento;
use App\Models\EquipeSaude;

use App\Models\Paciente;
use App\Models\Procedimento;
use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\TipoProcedimento;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TabelaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        /**
         * TABELA: tipo_procedimentos
         */
        $dataTipoProcedimento = [
            ['tipo_procedimento_nome' => 'Consulta'],
            ['tipo_procedimento_nome' => 'Exame'],
            ['tipo_procedimento_nome' => 'Outros'],
        ];
        TipoProcedimento::insert($dataTipoProcedimento);

        /**
         * TABELA: procedimentos
         */
        $dataProcedimento = [
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta/Diagnostico/Avaliação de Glaucoma'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Alergia Alimentar Infantil'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Alergia e Imunologia - Pediatrica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Angiologia - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Angiologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cardiologia - Arritimia'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cardiologia - Avaliação Cateterismo'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cardiologia - Insuficiencia Cardiaca'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cardiologia Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cardiologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Cardiaca'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Geral - Campanha de Cirúrgias Eletivas'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Geral - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Ginecologica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Ginecologica - Conização'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Cirúrgia Pediatrica - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Clínica Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Dermatologia - Epidermolise Bolhosa'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Dermatologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Endocrinologia e Metabologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Endocrinologia - Geral Pediatrica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Fonoaudiologia - Infantil'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Gastroenterologia - Colangiopancreatografia (Cpre)'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Gastroenterologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Gastroenterologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Genetica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Geneticista - Infantil'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Geriatria - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ginecologia -Endometriose'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ginecologia - Planejamento Familiar'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ginecologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Hebiatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Hematologia - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Hematologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Imunologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Infectologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Mastologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Nefrologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Nefrologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Neurologia - Disturbio do Movimento'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Neurologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Neurologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Neurologia -Triagem'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Nutricao - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Nutricao - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Nutrologia - Infantil'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Obstetricia Alto Risco - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Oftalmologia - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Oftalmologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Oncologia Clínica Pediatrica - Triagem'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Oncologia Clínica - Triagem'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Oncologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ortopedia - Adulto - Trauma Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ortopedia - Joelho'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ortopedia - Mao'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ortopedia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Ortopedia - Quadril'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Otorrinolaringologia - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Otorrinolaringologia Pediatrica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Otorrinolaringologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Pediatria - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Pediatria - Reumatologia'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Pneumologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Pneumologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Proctologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Psicologia Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Psicologia - Pediatria'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Psiquiatria - Geral'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Psiquiatria - Pediatrica'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Reumatologia - Regulação'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Terapia Ocupacional Infantil'],
            ['procedimento_tipo_id' => 1, 'procedimento_nome' => 'Consulta em Urologia - Regulação'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Atendimento Clinico P/ Indicação, Fornecimento e Insercao do DIU'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Avaliação em Cirúrgia Oftalmologica'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Biópsia de Próstata Via Transretal'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Colonoscopia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Colonoscopia - I'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Colposcopia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Densitometria'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Ecocardiografia Transtoracica'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Ecocardiografia Transtoracica - Pediatria'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Ecocardiograma - Adulto'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Eletrocardiograma'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Eletrocardiograma Com Laudo'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Eletroencefalograma'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Endoscopia Digestiva Alta'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Exame Citopatologico Cervico-Vaginal/Microflora'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Fibrolaringoscopia (Laringoscopia)'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Anatomopatologia e Citopatologia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Cirúrgia de Catarata'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Cirúrgia Oftalmologica'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Coleta de Material por Meio de Puncao/Biopsia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo -¬ Consultas Doencas Cronicas'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Consultas Especializadas'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnose em Cardiologia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnose em Ginecologia/Obstetricia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnose em Oftalmologia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnostico por Medicina Nuclear'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnostico por Ressonancia Magnetica'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Diagnostico por Tomografia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Endoscopia Digestiva'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Exames Ultra-Sonograficos'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Exames Ultra-Sonograficos - Infantil'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Mamografia'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Patologia Clínica (Exames de Laboratorio)'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Projeto Respirar'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Radiodiagnostico'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Radiodiagnostico (Grupo 13)'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Ressonancia Magnetica'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Grupo - Terapias Especializadas (por Terapia)'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Mamografia Bilateral Para Rastreamento'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Monitorização Ambulatorial de Pressao Arterial (M.A.P.A)'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Puncao Aspirativa Da Tireoide'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Puncao de Mama por Agulha Grossa'],
            ['procedimento_tipo_id' => 2, 'procedimento_nome' => 'Teste de Esforco / Teste Ergometrico'],
        ];
        Procedimento::insert($dataProcedimento);

        /**
         * TABELA: setores
         */
        $dataSetor = [
            ['setor_nome' => 'Recepção'],
            ['setor_nome' => 'Marcação'],
            ['setor_nome' => 'Marcação Externa'],
            ['setor_nome' => 'Paciente'],
        ];
        Setor::insert($dataSetor);

        User::factory()->create([
            'name'  => 'User Admin',
            'email' => 'admin@email.com',
        ]);

        User::factory()->create([
            'name'  => 'User Recepção',
            'email' => 'recepcao@email.com',
            'setor_id' => 1
        ]);

        User::factory()->create([
            'name'  => 'User Marcação',
            'email' => 'marcacao@email.com',
            'setor_id' => 2
        ]);

        User::factory()->create([
            'name'  => 'User Gestor',
            'email' => 'gestor@email.com',
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
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Claudeane França de Lima', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Eliene Maria Silva dos Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Maria Clemilda da Conceição', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Maria Edneide Nascimento', 'agente_saude_apelido' => 'Nina'],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Rosilene Lima do Nascimento', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Solange Neves dos Santos', 'agente_saude_apelido' => 'Sol'],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Viviane Pereira da Silva', 'agente_saude_apelido' => 'Vivi'],
            ['agente_saude_equipe_saude_id' => 1, 'agente_saude_nome' => 'Sandra Petrucia da Silva', 'agente_saude_apelido' => null],

            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Bruna Silva dos Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Daniela Silva de Souza', 'agente_saude_apelido' => 'Dany'],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Ednaldo Pedro Silva do Nascimento', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Genilza Maria Santos Nobre', 'agente_saude_apelido' => 'Gena'],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Janeide dos Santos Bezerra', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Laura Silva Neta', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Rosilene Maria da Conceicao Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Jéssica Batista da Silva', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 2, 'agente_saude_nome' => 'Cristhyla Janielle Silva dos Santos', 'agente_saude_apelido' => 'Janinha'],

            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Dulce Maria dos Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'José Aurino da Piedade', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'José Francisco de Oliveira', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Maria Janiele Martins dos Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Maria Jose Pereira da Silva', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Mayara Vieira da Silva', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 3, 'agente_saude_nome' => 'Claudiane Vitor', 'agente_saude_apelido' => null],

            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Cicera Maria Lima Alves', 'agente_saude_apelido' => 'Sô'],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Eduardo de Lima Vasco', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Elias Silva de Lima', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Gilberto Amaro dos Santos', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Jose Aílton do Nascimento', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 4, 'agente_saude_nome' => 'Rosiberto Nascimento Silva', 'agente_saude_apelido' => null],

            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Bruno Mendonça de Carvalho', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Edna Lucia Silva do Nascimento Vasco', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Fernando Lourenço de Lima', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Maria Cícera Santos do Nascimento', 'agente_saude_apelido' => 'Cícera'],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Simone Fernandes Oliveira Lima', 'agente_saude_apelido' => 'Lela'],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Viviane Santos Silva', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 5, 'agente_saude_nome' => 'Robson Buarque Aires', 'agente_saude_apelido' => 'Binho'],

            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Jose Cícero da Silva Lima', 'agente_saude_apelido' => 'Cicinho'],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Macirleide Cristina do Nascimento Melo', 'agente_saude_apelido' => null],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Maria da Conceição Nascimento Silva', 'agente_saude_apelido' => 'Nena'],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Rosineide Lima do Nascimneto', 'agente_saude_apelido' => 'Neide'],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Valdinês Sales Lamenha', 'agente_saude_apelido' => 'Ney'],
            ['agente_saude_equipe_saude_id' => 6, 'agente_saude_nome' => 'Carla Patrícia dos Santos Melo', 'agente_saude_apelido' => null],
        ];
        AgenteSaude::insert($dataAgenteSaude);

        /**
         * TABELA: pacientes
         */
        $dataPaciente = [];

        for ($i = 1; $i <= 10; $i++) {
            $dataPaciente[] = [
                'paciente_agente_saude_id' => rand(1, 6),
                'paciente_cpf'             => $faker->cpf(true),
                'paciente_nome'            => $faker->name(),
                'paciente_sexo'            => $faker->randomElement(['Masculino', 'Feminino']),
                'paciente_data_nascimento' => $faker->date(),
                'paciente_nome_mae'        => $faker->name('female'),
                'paciente_endereco'        => $faker->address(),
                'paciente_contato'         => '(82) ' . rand(90000, 99999) . '-' . rand(1000, 9999),
                'paciente_cns'             => $faker->numerify('###############'),
            ];
        }
        Paciente::insert($dataPaciente);

        /**
         * TABELA: atendimentos
         * Número gerado no formato AAAAMMDDNNN
         */
        // $dataAtendimento = [];
        // $dataAtual       = date('Ymd');

        // for ($i = 1; $i <= 10; $i++) {
        //     $sequencia         = formatoId($i, 3);
        //     $numeroAtendimento = $dataAtual . $sequencia;
        //     $dataAtendimento[] = [
        //         'atendimento_paciente_id' => $i,
        //         'atendimento_numero'      => $numeroAtendimento,
        //         'atendimento_data'        => now(),
        //     ];
        // }
        // Atendimento::insert($dataAtendimento);

        /**
         * TABELA: solicitações
         * Inclui enum solicitacao_status
         */
        // $statusList = ['aguardando', 'agendado', 'marcado', 'entregue', 'cancelado'];

        // $dataSolicitacao = [];

        // for ($i = 1; $i <= 10; $i++) {
        //     $procedimentoId = rand(1, 9); // ajustável conforme a quantidade real de procedimentos

        //     $dataSolicitacao[] = [
        //         'solicitacao_atendimento_id'       => $i,
        //         'solicitacao_procedimento_id'      => $procedimentoId,
        //         'solicitacao_localizacao_atual_id' => null, // será atualizado conforme a movimentação
        //         'solicitacao_numero'               => $faker->unique()->numerify('S######'),
        //         'solicitacao_data'                 => $faker->dateTimeBetween('-30 days', 'now'),
        //         'solicitacao_status'               => $faker->randomElement($statusList),
        //         'created_user_id'                  => 1,
        //         'updated_user_id'                  => 1,
        //         'created_at'                       => now(),
        //         'updated_at'                       => now(),
        //     ];
        // }

        // Solicitacao::insert($dataSolicitacao);

        // $solicitacoes = Solicitacao::all();

        // foreach ($solicitacoes as $solicitacao) {
        //     $movimentos = [];

        //     // Movimentação inicial: recepção -> marcação (encaminhamento)
        //     $movimentos[] = [
        //         'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
        //         'movimentacao_usuario_id'     => 1, // recepcionista
        //         'movimentacao_destino_id'     => null, // setor marcação pode ser preenchido
        //         'movimentacao_tipo'           => 'encaminhamento',
        //         'movimentacao_entregue_para'  => null,
        //         'movimentacao_observacao'     => 'Encaminhada para marcação',
        //         'movimentacao_data'           => $faker->dateTimeBetween($solicitacao->solicitacao_data, 'now'),
        //     ];

        //     // Se o status for 'marcada', gerar retorno para recepção
        //     if ($solicitacao->solicitacao_status === 'marcada') {
        //         $movimentos[] = [
        //             'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
        //             'movimentacao_usuario_id'     => 2, // setor de marcação
        //             'movimentacao_destino_id'     => null, // retorno para recepção
        //             'movimentacao_tipo'           => 'retorno',
        //             'movimentacao_entregue_para'  => null,
        //             'movimentacao_observacao'     => 'Retorno da marcação',
        //             'movimentacao_data'           => $faker->dateTimeBetween($solicitacao->solicitacao_data, 'now'),
        //         ];

        //         // Movimentação de entrega ao paciente/ACS/Equipe
        //         $entreguePara = $faker->randomElement(['paciente', 'agente_saude', 'equipe_saude']);
        //         $movimentos[] = [
        //             'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
        //             'movimentacao_usuario_id'     => 1, // recepcionista
        //             'movimentacao_destino_id'     => null,
        //             'movimentacao_tipo'           => 'entrega',
        //             'movimentacao_entregue_para'  => $entreguePara,
        //             'movimentacao_observacao'     => 'Entrega realizada',
        //             'movimentacao_data'           => now(),
        //         ];
        //     }

        //     // Inserir todas movimentações geradas
        //     SolicitacaoMovimentacao::insert($movimentos);
        // }
    }
}
