<?php

use App\Models\AnoLetivo;
use App\Models\TipoEnsino;
use App\Models\TabelaPreco;
use App\Models\Serie;
use App\Models\Configuracao;
use App\Models\Emitente;

use App\Models\Paciente;
use App\Models\Atendimento;
use App\Models\Solicitacao;
use App\Models\Protocolo;

function quantitativoPacientes($periodo){
	if($periodo == 'hoje'){
		return Paciente::whereDay('created_at', date('d'))->count();
	}elseif($periodo == 'esta_semana'){
		return Paciente::whereRaw('created_at > (NOW() - INTERVAL 7 DAY)')->count();
	}elseif($periodo == 'este_mes'){
		return Paciente::whereMonth('created_at', date('m'))->count();
	}elseif($periodo == 'este_ano'){
		return Paciente::whereYear('created_at', date('Y'))->count();
	}else{
		return 0;
	}	
}

function quantitativoAtendimentos($periodo){
	if($periodo == 'hoje'){
		return Atendimento::whereDay('atendimento_data', date('d'))->count();
	}elseif($periodo == 'esta_semana'){
		return Atendimento::whereRaw('atendimento_data > (NOW() - INTERVAL 7 DAY)')->count();
	}elseif($periodo == 'este_mes'){
		return Atendimento::whereMonth('atendimento_data', date('m'))->count();
	}elseif($periodo == 'este_ano'){
		return Atendimento::whereYear('atendimento_data', date('Y'))->count();
	}else{
		return 0;
	}	
}

function quantitativoSolicitacoes($periodo){
	if($periodo == 'hoje'){
		return Solicitacao::whereDay('solicitacao_data', date('d'))->count();
	}elseif($periodo == 'esta_semana'){
		return Solicitacao::whereRaw('solicitacao_data > (NOW() - INTERVAL 7 DAY)')->count();
	}elseif($periodo == 'este_mes'){
		return Solicitacao::whereMonth('solicitacao_data', date('m'))->count();
	}elseif($periodo == 'este_ano'){
		return Solicitacao::whereYear('solicitacao_data', date('Y'))->count();
	}else{
		return 0;
	}	
}

function quantitativoProtocolos($periodo){
	if($periodo == 'hoje'){
		return Protocolo::whereDay('protocolo_data', date('d'))->count();
	}elseif($periodo == 'esta_semana'){
		return Protocolo::whereRaw('protocolo_data > (NOW() - INTERVAL 7 DAY)')->count();
	}elseif($periodo == 'este_mes'){
		return Protocolo::whereMonth('protocolo_data', date('m'))->count();
	}elseif($periodo == 'este_ano'){
		return Protocolo::whereYear('protocolo_data', date('Y'))->count();
	}else{
		return 0;
	}	
}

function configuracoes()
{
	$configuracao = Configuracao::where('id', 1)->limit(1)->orderBy('id', 'desc')->first();

	//Registros por Página
	if($configuracao->qtde_registros_por_pagina != ''){
		$registros_por_pagina = explode(';', $configuracao->qtde_registros_por_pagina);
	}

	return [
		'tecla_atalho_nova_tarefa' => $configuracao->tecla_atalho_nova_tarefa ?? 'F8',
		'tecla_atalho_consulta_tarefas' => $configuracao->tecla_atalho_consulta_tarefas ?? 'F10',
		'qtde_registros_por_pagina' => $registros_por_pagina ?? ['10', '25', '100', '200']
	];
}

function emitente()
{
	$emitente = Emitente::where('emitente_id', 1)->limit(1)->first();

	return collect([
		'emitente_razao_social' => $emitente->emitente_razao_social ?? '[- Razão Social do Emitente -]',
		'emitente_cnpj' => $emitente->emitente_cnpj ?? '99.999.999/9999-99',
		'emitente_endereco' => $emitente->emitente_endereco ?? '[- Endereço do emitente -]',
		'emitente_numero' => $emitente->emitente_numero ?? '[- 999 -]',
		'emitente_bairro' => $emitente->emitente_bairro ?? '[- Bairro do emitente -]',
		'emitente_cidade_uf' => $emitente->emitente_cidade_uf ?? '[- Cidade do emitente -]',
		'emitente_uf' => $emitente->municipio->estado->estado_uf ?? 'AA',
		'emitente_contato' => $emitente->emitente_contato ?? '(82) 99999-9999',
		'emitente_email' => $emitente->emitente_email ?? 'emaildoemitente@email.com',
		'emitente_site' => $emitente->emitente_site ?? 'sitedoemitente.com.br',
		'emitente_logo_perfil' => $emitente->emitente_logo_perfil ?? 'logo-perfil-emitente.jpg',
		'emitente_logo_documentacao' => $emitente->emitente_logo_documentacao ?? 'logo-documentacao-emitente.jpg'
	]);
}

function numeroAtendimento(){
    if(Atendimento::whereYear('atendimento_data', date('Y'))->whereMonth('atendimento_data', date('m'))->whereDay('atendimento_data', date('d'))->orderBy('atendimento_data', 'desc')->exists()){
		$atendimento = Atendimento::whereYear('atendimento_data', date('Y'))->whereMonth('atendimento_data', date('m'))->whereDay('atendimento_data', date('d'))->orderBy('atendimento_data', 'desc')->limit(1)->first();

    	return date('Y').''.date('m').''.date('d').''.formatoId(substr($atendimento->atendimento_numero, 8, 3) + 1, 3);
    }else{
    	return date('Y').''.date('m').''.date('d').''.formatoId(1, 3);
    }
}

function numeroSolicitacao(){
    if(Solicitacao::whereYear('solicitacao_data', date('Y'))->orderBy('solicitacao_numero', 'desc')->exists()){
		$solicitacao = Solicitacao::orderBy('solicitacao_numero', 'desc')->limit(1)->first();

    	return date('Y').''.formatoId(substr($solicitacao->solicitacao_numero, 8, 6) + 1, 6);
    }else{
    	return date('Y').''.formatoId(1, 6);
    }
}

function numeroProtocolo(){
    if(Protocolo::whereYear('protocolo_data', date('Y'))->orderBy('protocolo_data', 'desc')->exists()){
		$protocolo = Protocolo::whereYear('protocolo_data', date('Y'))->orderBy('protocolo_data', 'desc')->limit(1)->first();

    	return date('Y').formatoId(substr($protocolo->protocolo_numero, 4, 6) + 1, 6);
    }else{
    	return date('Y').formatoId(1, 6);
    }
}

function numeroMovimentoReceita($exercicio){
    $movimento_receita = new MovimentoReceita();

    $numeroMovimentoReceita = $exercicio;

    $verifica = $movimento_receita
        ->join('movimentos_financeiro', 'movimentos_receita.movimento_receita_movimento_financeiro_id', '=', 'movimentos_financeiro.movimento_financeiro_id')
        ->whereYear('movimento_financeiro_data', $exercicio)
        ->orderBy('movimento_receita_id', 'desc')
        ->limit(1)
        ->first();

    if($verifica){
        return $numeroMovimentoReceita.''.formatoId(substr($verifica->movimento_receita_numero, 4, 4) + 1, 4);
    }else{
        return $numeroMovimentoReceita.''.formatoId(1, 4);
    }
}

function numeroMovimentoDespesa($exercicio){
    $movimento_despesa = new MovimentoDespesa();

    $numeroMovimentoDespesa = $exercicio;

    $verifica = $movimento_despesa
        ->join('movimentos_financeiro', 'movimentos_despesa.movimento_despesa_movimento_financeiro_id', '=', 'movimentos_financeiro.movimento_financeiro_id')
        ->whereYear('movimento_financeiro_data', $exercicio)
        ->orderBy('movimento_despesa_id', 'desc')
        ->limit(1)
        ->first();

    if($verifica){
        return $numeroMovimentoDespesa.''.formatoId(substr($verifica->movimento_despesa_numero, 4, 4) + 1, 4);
    }else{
        return $numeroMovimentoDespesa.''.formatoId(1, 4);
    }
}

function verifica_tabelas_preco()
{
	$tipos_ensino = TipoEnsino::where('tipo_ensino_status', 1)->get();

	foreach($tipos_ensino as $tipo_ensino){
		if(TabelaPreco::where('tabela_preco_ano_letivo_id', ano_letivo_atual('id'))->where('tabela_preco_tipo_ensino_id', $tipo_ensino->tipo_ensino_id)->where('tabela_preco_tipo', 'Matrícula')->exists() and TabelaPreco::where('tabela_preco_ano_letivo_id', ano_letivo_atual('id'))->where('tabela_preco_tipo_ensino_id', $tipo_ensino->tipo_ensino_id)->where('tabela_preco_tipo', 'Mensalidade')->exists()){
			return false;
		}else{
			return true;
		}
	}
}

function periodoDia()
{
	$horario = date('H:i:s');
	$periodo = '';

	if($horario > '00:00:00' and $horario < '06:00:00'){
		$periodo = 'madrugada';
	}elseif($horario > '06:00:00' and $horario < '12:00:00'){
		$periodo = 'manha';
	}elseif($horario > '12:00:00' and $horario < '18:00:00'){
		$periodo = 'tarde';
	}elseif($horario > '18:00:00' and $horario < '00:00:00'){
		$periodo = 'noite';
	}

	return $periodo;
}

function idadeSerie($data_nascimento, $serie_id)
{
	$serie = \App\Models\Serie::where('serie_id', $serie_id)->first();

	if(idadeLetiva($data_nascimento) >= $serie->serie_idade_inicial and idadeLetiva($data_nascimento) < $serie->serie_idade_final and $data_nascimento <= converteData(env('DIA_MES_LIMITE_IDADE_SERIE').'/'.ano_letivo_atual())){
		return true;
	}else{
		return false;
	}	
}

function idadeSerieReal($data_nascimento)
{
	$idade = idadeLetiva($data_nascimento);
	$data_limite = converteData(env('DIA_MES_LIMITE_IDADE_SERIE').'/'.ano_letivo_atual());

	$series = \App\Models\Serie::get();

	foreach($series as $serie){
		if(idadeLetiva($data_nascimento) >= $serie->serie_idade_inicial and idadeLetiva($data_nascimento) < $serie->serie_idade_final and $data_nascimento <= converteData(env('DIA_MES_LIMITE_IDADE_SERIE').'/'.ano_letivo_atual())){
			return $serie->serie_nome;
		}
	}	

	return false;	
}

function reservaVagaDisponivel($serie_id, $turno_id){
	$reserva_vaga = new App\Models\ReservaVaga();
	$serie = Serie::where('serie_id', $serie_id)->first();

	$total_turmas = Turma::where('turma_serie_id', $serie_id)
		->where('turma_turno_id', $turno_id)
		->count();

    $total_reserva = $reserva_vaga
    	->where('reserva_vaga_ano_letivo_id', ano_letivo_atual('id'))
    	->where('reserva_vaga_serie_id', $serie_id)
    	->where('reserva_vaga_turno_id', $turno_id)
    	->where('reserva_vaga_status', '!=', 0)
    	->count();

    $vagas_disponiveis = 0 - $total_reserva;
    $vagas_disponiveis = ($serie->serie_limite_turma * $total_turmas) - $total_reserva;
    
    return $vagas_disponiveis;
}

function vagaDisponivel($serie_id, $turma_id){
	$matricula = new App\Models\Matricula();
	$serie = Serie::where('serie_id', $serie_id)->first();

	$total_vaga = $matricula
		->join('turmas', 'matriculas.matricula_turma_id', '=', 'turmas.turma_id')
		->where('matricula_ano_letivo_id', ano_letivo_atual('id'))
		->where('matricula_turma_id', $turma_id)
		->where('turma_serie_id', $serie_id)
		->where('matricula_status', '!=', 0)
		->count();

    $vagas_disponiveis = $serie->serie_limite_turma - $total_vaga;
    
    return $vagas_disponiveis;
}

function numeroProntuario(){
    if(Prontuario::orderBy('prontuario_data', 'desc')->exists()){
		$prontuario = Prontuario::orderBy('prontuario_data', 'desc')->limit(1)->first();

    	return 'P'.formatoId(substr($prontuario->prontuario_numero, 1, 5) + 1, 5);
    }else{
    	return 'P'.formatoId(1, 5);
    }
}



function codigoResponsavel(){
	$responsavel = new App\Models\Responsavel();
    $verifica = $responsavel->orderBy('responsavel_codigo', 'desc')->limit(1)->first();

    if($verifica){
    	return 'R'.formatoId(substr($verifica->responsavel_codigo, 2, 6) + 1, 6);
    }else{
    	return 'R'.formatoId(1, 6);
    }
}

function codigoReservaVaga(){
	return strtoupper(uniqid());
}

function codigoMatricula(){
	$matricula = new App\Models\Matricula();
    $verifica = $matricula->where('matricula_ano_letivo_id', ano_letivo_atual('id'))->orderBy('matricula_id', 'desc')->limit(1)->first();

    if($verifica){
    	return ano_letivo_atual().''.formatoId(substr($verifica->matricula_codigo, 5, 4) + 1, 4);
    }else{
    	return ano_letivo_atual().''.formatoId(1, 4);
    }
}

function ano_letivo_atual($parametro = 'nome')
{
	$ano_letivo = AnoLetivo::where('ano_letivo_atual', 1)->first();

	if($ano_letivo){
		if($parametro == 'id'){
			return $ano_letivo->ano_letivo_id;
		}elseif($parametro == 'nome'){
			return $ano_letivo->ano_letivo_nome;
		}
	}else{
		return false;
	}	
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function protocolosAReceber()
{
	$protocolo = new App\Models\Processo\Movimentacao();

	return $protocolo
		->whereNull('movimentacao_data_recebido')
		->where('movimentacao_setor_id', auth()->user()->setor_id)
		->count();
}

function numeroProcesso($secretaria_id){

	$processo = new App\Models\Processo\Processo();
	$secretaria = new App\Models\Gerencial\Secretaria();

	$secretaria = $secretaria->where('secretaria_id', $secretaria_id)->first();

	date_default_timezone_set('America/Sao_Paulo');
    $agora = date('Y-m-d H:i:s');
    $ano = date('Y');

    $secretaria_codigo = $secretaria->secretaria_codigo;

    $verifica = $processo->where('processo_data_abertura', '<', $agora)->orderBy('processo_id', 'desc')->limit(1)->first();

    if($verifica){
    	return $secretaria_codigo.'.'.formatoId(substr($verifica->processo_numero, 5, 6) + 1, 6).'/'.$ano;
    }else{
    	return $secretaria_codigo.'.'.formatoId(1, 6).'/'.$ano;
    }
}

function codigoSecretaria(){
	$secretaria = new App\Models\Gerencial\Secretaria();

    $verifica = $secretaria->orderBy('secretaria_id')->limit(1)->first();

    if($verifica){
    	return formatoId(substr($verifica->secretaria_codigo, 0, 2) + 1, 2).'00';
    }else{
    	return formatoId(1, 2).'00';
    }
}

function codigoSetor($secretaria_id){
	$setor = new App\Models\Gerencial\Setor();
	$secretaria = new App\Models\Gerencial\Secretaria();

	$secretaria = $secretaria->where('secretaria_id', $secretaria_id)->first();
    $verifica = $setor->where('setor_secretaria_id', $secretaria_id)->orderBy('setor_id', 'asc')->limit(1)->first();

    if($verifica){
    	return substr($secretaria->secretaria_codigo, 0, 2).''.formatoId(substr($verifica->setor_codigo, 2, 2) + 1, 2);
    }else{
    	return substr($secretaria->secretaria_codigo, 0, 2).''.formatoId(1, 2);
    }
}

function codigoPasse($data){
	$passe = new App\Models\PasseLivre\Passe();

	$ano = date('Y', strtotime($data));

    $verifica = $passe->whereYear('passe_data_expedicao', $ano)->orderBy('passe_id')->limit(1)->first();

    if($verifica){
    	return formatoId(substr($verifica->passe_codigo, 0, 3) + 1, 3).'-'.$ano;
    }else{
    	return formatoId(1, 3).'-'.$ano;
    }
}

function diaSemanaMes($data){
	return $diasemana_numero = date('w', strtotime($data));
}

function get_cliente_ip() {
	$ipaddress = '';
	if(isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}

function numeracaoDiarioClasse($diario_classe_id){
	$dc_item = new \App\Models\DiarioClasseItem();

	$item = $dc_item->where('dc_item_diario_classe_id', $diario_classe_id)->orderBy('dc_item_numeracao', 'desc')->first();

	$numeracao = 1;
	if($item){
		$numeracao = $item->dc_item_numeracao + 1;
	}

	return $numeracao;
}

//GERA NÚMERO ATENDIMENTO
function numeroMatricula($anoLeticoIdMatricula){
	$pdo = new Model();

	$anoLetivo = $pdo->db->select('SELECT * FROM ano_letivo WHERE idAnoLetivo = '.$anoLeticoIdMatricula);

	$numeroMatricula = $anoLetivo[0]['anoLetivo'];

	$verifica = $pdo->db->select('SELECT * FROM matriculas WHERE anoLeticoIdMatricula = '.$anoLeticoIdMatricula.' ORDER BY idMatricula DESC LIMIT 1');
	
	if($verifica){
		$n = $numeroMatricula.''.formatoId(substr($verifica[0]['numeroMatricula'], 4, 4) + 1, 3);
	}else{
		$n = $numeroMatricula.''.formatoId(1, 3);
	}

	return $n;
}

function calcularJuros($dias, $valor){
	$valorJuros = ($dias * $valor * PERCENTUAL_JUROS_DIA) / 100;

	return $valorJuros;
}

function calculoNota($matriculaId, $bimestreId, $disciplinaId){
	$pdo = new Model();

	$relacaoNotas = $pdo->db->select('SELECT * FROM registro_aproveitamentos_alunos INNER JOIN registro_aproveitamentos ON registro_aproveitamentos_alunos.aproveitamentoId=registro_aproveitamentos.idAproveitamento WHERE matriculaIdAproveitamentoAluno = '.$matriculaId.' and aproveitamentoBimestralIdAproveitamento = '.$bimestreId.' and disciplinaIdAproveitamentoAluno = '.$disciplinaId);

	$calculoNota = 0;
	foreach ($relacaoNotas as $key => $value) {
		if(TIPO_CALCULO_NOTAS == 'soma'){
			$calculoNota += $value['notaAproveitamento'];
		}else{
			$calculoNota += $value['notaAproveitamento'];
		}
	}

	if($relacaoNotas){
		return $calculoNota;
	}else{
		return false;
	}	
}

function bloquear()
{	
	$bloqueado = Session::get('bloquear');

	if($_GET['url'] == 'bloquear'){

	}elseif($_GET['url'] == 'desbloquear'){

	}elseif($_GET['url'] == 'dashboard/bloquear'){

	}elseif($_GET['url'] == 'dashboard'){
		Session::set('url_retorno', 'dashboard');
	}elseif($_GET['url'] == 'dashboard/xhrGetListings'){
		Session::set('url_retorno', 'dashboard');
	}elseif($_GET['url'] == ''){
		Session::set('url_retorno', 'dashboard');
	}else{
		Session::set('url_retorno', $_GET['url']);
	}

	if($bloqueado == true){
		header('Location: '.URL.'bloquear');
		exit;
	}

	$tempoExpiracao = Session::get('tempoExpiracao');
	if($tempoExpiracao){
		$segundos = time() - $tempoExpiracao;
	}

	if($segundos > LIMITE_EXPIRACAO){
		Session::set('bloquear', true);

		header('Location: '.URL.'bloquear');
		exit;
	}else{
		Session::set('tempoExpiracao', time());
	}
}

function tempoCorrido($time){
	date_default_timezone_set('America/Recife');

	$now = strtotime(date('m/d/Y H:i:s', time()+3));
	$time = strtotime($time);
	$diff = $now - $time;

	$seconds = $diff;
	$minutes = round($diff / 60);
	$hours = round($diff / 3600);
	$days = round($diff / 86400);
	$weeks = round($diff / 604800);
	$months = round($diff / 2419200);
	$years = round($diff / 29030400);

	if ($seconds <= 60) return"alguns segundos atrás";
	else if ($minutes <= 60) return $minutes==1 ?'1 minuto atrás':$minutes.' minutos atrás';
	else if ($hours <= 24) return $hours==1 ?'1 hora atrás':$hours.' horas atrás';
	else if ($days <= 7) return $days==1 ?'1 dia atras':$days.' dias atrás';
	else if ($weeks <= 4) return $weeks==1 ?'1 semana atrás':$weeks.' semanas atrás';
	else if ($months <= 12) return $months == 1 ?'1 mês atrás':$months.' meses atrás';
	else return $years == 1 ? 'um ano atrás':$years.' anos atrás';
}

function campoDocumento($data){
	if(array_keys($data)[0] == 'matricula'){
		//Tabela dos Horários de Funcionamento
		$tabela_horarios_funcionamento = '';
		$tabela_horarios_funcionamento .= '<table border="1" width="50%" style="border-collapse:collapse;margin:10px auto 0 auto">';
			$tabela_horarios_funcionamento .= '<tr>';
				$tabela_horarios_funcionamento .= '<th width="50%">Turno</th>';
				$tabela_horarios_funcionamento .= '<th width="25%">Início</th>';
				$tabela_horarios_funcionamento .= '<th width="25%">Término</th>';
			$tabela_horarios_funcionamento .= '</tr>';
		foreach($data['matricula']->ano_letivo->horarios_funcionamento as $value){
			$tabela_horarios_funcionamento .= '<tr>';
				$tabela_horarios_funcionamento .= '<td align="center">'.$value->turno->turno_nome.'</td>';
				$tabela_horarios_funcionamento .= '<td align="center">'.$value->horario_inicial.'</td>';
				$tabela_horarios_funcionamento .= '<td align="center">'.$value->horario_final.'</td>';
			$tabela_horarios_funcionamento .= '</tr>';
		}
		$tabela_horarios_funcionamento .= '</table>';

		//Tabela do Informe de Pagamento
		$tabela_informe_pagamento = '';
		$tabela_informe_pagamento .= '<table border="0" width="100%" style="border-collapse:collapse;margin:0 auto">';
			$tabela_informe_pagamento .= '<tr>';
				$tabela_informe_pagamento .= '<th width="" align="left">Vencimento</th>';
				$tabela_informe_pagamento .= '<th width="" align="left">Recebimento</th>';
				$tabela_informe_pagamento .= '<th width="" align="left">Referência</th>';
				$tabela_informe_pagamento .= '<th width="" align="right">Valor Documento</th>';
				$tabela_informe_pagamento .= '<th width="" align="right">Valor Pago</th>';
			$tabela_informe_pagamento .= '</tr>';

			$total_informe_pagamento_documento = 0;
			$total_informe_pagamento_pago = 0;
			foreach($data['matricula']->mensalidades as $value){
				$tabela_informe_pagamento .= '<tr>';
					$tabela_informe_pagamento .= '<td align="left">'.formatoData($value->mensalidade_data_vencimento).'</td>';
					if($value->pagamento){
						$tabela_informe_pagamento .= '<td align="left">'.formatoData($value->pagamento->mens_pagamento_data).'</td>';
					}else{
						$tabela_informe_pagamento .= '<td align="left"></td>';
					}
					$tabela_informe_pagamento .= '<td align="left">Mensalidade</td>';
					$tabela_informe_pagamento .= '<td align="right">'.formatoValor($value->mensalidade_valor).'</td>';
					if($value->pagamento){
						$tabela_informe_pagamento .= '<td align="right">'.formatoValor($value->pagamento->mens_pagamento_valor_pago).'</td>';
					}else{
						$tabela_informe_pagamento .= '<td align="right"></td>';
					}
				$tabela_informe_pagamento .= '</tr>';

				$total_informe_pagamento_documento += $value->mensalidade_valor;
				if($value->pagamento){
					$total_informe_pagamento_pago += $value->pagamento->mens_pagamento_valor_pago;
				}else{
					$total_informe_pagamento_pago += 0;
				}
			}
			$tabela_informe_pagamento .= '<tr>';
				$tabela_informe_pagamento .= '<th width="" align="left" colspan="3">Total Geral</th>';
				$tabela_informe_pagamento .= '<th width="" align="right">'.formatoValor($total_informe_pagamento_documento).'</th>';
				$tabela_informe_pagamento .= '<th width="" align="right">'.formatoValor($total_informe_pagamento_pago).'</th>';
			$tabela_informe_pagamento .= '</tr>';
		$tabela_informe_pagamento .= '</table>';

		return array(
		    #Responsavel
            '[[NOME_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_nome ?? '',
            '[[NACIONALIDADE_RESPONSAVEL]]' => ($data['matricula']->responsavel->responsavel_nacionalidade == '1' ? 'Brasileira' : 'Estrangeira') ?? '',
            '[[ESTADO_CIVIL_RESPONSAVEL]]' => $data['matricula']->responsavel->estado_civil->estado_civil_nome ?? '',
            '[[PROFISSAO_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_profissao ?? '',
            '[[SEXO_RESPONSAVEL]]' => ($data['matricula']->responsavel->responsavel_sexo == '' ? 'Feminino' : 'Masculino') ?? '',
            '[[RG_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_rg ?? '',
            '[[CPF_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_cpf ?? '',
            '[[NOME_ENDERECO_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_endereco ?? '',
            '[[NUMERO_ENDERECO_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_numero ?? '',
            '[[BAIRRO_ENDERECO_RESPONSAVEL]]' => $data['matricula']->responsavel->responsavel_bairro ?? '',
            '[[CIDADE_ENDERECO_RESPONSAVEL]]' => $data['matricula']->responsavel->municipio->municipio_nome ?? '',
            '[[UF_ENDERECO_RESPONSAVEL]]' => $data['matricula']->responsavel->municipio->estado->estado_uf ?? '',
            #Entidade
            '[[RAZAO_SOCIAL]]' => escola()->get('escola_razao_social') ?? '',
            '[[NOME_ENDERECO_ESCOLA]]' => escola()->get('escola_endereco') ?? '',
            '[[NUMERO_ENDERECO_ESCOLA]]' => escola()->get('escola_numero') ?? '',
            '[[BAIRRO_ENDERECO_ESCOLA]]' => escola()->get('escola_bairro') ?? '',
            '[[CIDADE_ENDERECO_ESCOLA]]' => escola()->get('escola_municipio') ?? '',
            '[[UF_ENDERECO_ESCOLA]]' => escola()->get('escola_uf') ?? '',
            '[[CNPJ_ESCOLA]]' => escola()->get('escola_cnpj') ?? '',
            '[[NOME_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_nome') ?? '',
            '[[NACIONALIDADE_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_nacionalidade') ?? '',
            '[[ESTADO_CIVIL_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_estado_civil') ?? '',
            '[[PROFISSAO_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_profissao') ?? '',
            '[[RG_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_rg') ?? '',
            '[[CPF_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_cpf') ?? '',
            '[[ENDERECO_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_endereco') ?? '',
            '[[NUMERO_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_numero') ?? '',
            '[[BAIRRO_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_bairro') ?? '',
            '[[CIDADE_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_municipio') ?? '',
            '[[UF_RESPONSAVEL_ESCOLA]]' => escola()->get('escola_responsavel_uf') ?? '',
            #Aluno
            '[[NOME_ALUNO]]' => $data['matricula']->aluno->aluno_nome ?? '',
            '[[DATA_NASCIMENTO_ALUNO]]' => formatoData($data['matricula']->aluno->aluno_data_nascimento) ?? '',
            '[[DATA_NASCIMENTO_EXTENSO_ALUNO]]' => dataExtenso($data['matricula']->aluno->aluno_data_nascimento) ?? '',
            '[[SEXO_ALUNO]]' => ($data['matricula']->aluno->aluno_sexo == '2' ? 'Feminimo' : 'Masculino') ?? '',
            '[[NATURALIDADE_ALUNO]]' => $data['matricula']->aluno->aluno_naturalidade ?? '',
            '[[NOME_ENDERECO_ALUNO]]' => $data['matricula']->aluno->aluno_endereco ?? '',
            '[[NUMERO_ENDERECO_ALUNO]]' => $data['matricula']->aluno->aluno_numero ?? '',
            '[[BAIRRO_ENDERECO_ALUNO]]' => $data['matricula']->aluno->aluno_bairro ?? '',
            '[[CIDADE_ENDERECO_ALUNO]]' => $data['matricula']->aluno->municipio->municipio_nome ?? '',
            '[[UF_ENDERECO_ALUNO]]' => $data['matricula']->aluno->municipio->estado->estado_uf ?? '',
            '[[NOME_PAI_ALUNO]]' => $data['matricula']->aluno->aluno_pai_nome ?? '',
            '[[NOME_MAE_ALUNO]]' => $data['matricula']->aluno->aluno_mae_nome ?? '',
            '[[RG_ALUNO]]' => $data['matricula']->aluno->aluno_rg ?? '',
            '[[CPF_ALUNO]]' => $data['matricula']->aluno->aluno_cpf ?? '',
            #Matrícula
            '[[ANO_LETIVO]]' => $data['matricula']->ano_letivo->ano_letivo_nome ?? '',
            '[[NOME_SERIE]]' => $data['matricula']->turma->serie->serie_nome ?? '',
            '[[NOME_TURNO]]' => $data['matricula']->turma->turno->turno_nome ?? '',
            '[[NOME_TURMA]]' => $data['matricula']->turma->turma_nome ?? '',
            '[[TIPO_ENSINO]]' => $data['matricula']->turma->serie->tipo_ensino->tipo_ensino_nome ?? '',
            '[[HORARIO_FUNCIONAMENTO_INICIO]]' => $data['matricula']->ano_letivo->horario_funcionamento_turno($data['matricula']->turma->turno->turno_id)->horario_inicial ?? '',
            '[[HORARIO_FUNCIONAMENTO_TERMINO]]' => $data['matricula']->ano_letivo->horario_funcionamento_turno($data['matricula']->turma->turno->turno_id)->horario_final ?? '',
            #Contrato
            '[[DATA_INICIO_CONTRATO]]' => formatoData($data['matricula']->ano_letivo->ano_letivo_inicio) ?? '',
            '[[DATA_INICIO_CONTRATO_EXTENSO]]' => dataExtenso($data['matricula']->ano_letivo->ano_letivo_inicio) ?? '',
            '[[DATA_TERMINO_CONTRATO]]' => formatoData($data['matricula']->ano_letivo->ano_letivo_termino) ?? '',
            #Mensalidade
            '[[VALOR_TOTAL_CONTRATO]]' => formatoValor($data['matricula']->valor_matricula() + $data['matricula']->total_geral_mensalidade()) ?? '',
            '[[VALOR_EXTENSO_TOTAL_CONTRATO]]' => valorExtenso($data['matricula']->valor_matricula() + $data['matricula']->total_geral_mensalidade()) ?? '',
            '[[VALOR_MATRICULA]]' => formatoValor($data['matricula']->valor_matricula()) ?? '',
            '[[VALOR_MATRICULA_EXTENSO]]' => valorExtenso($data['matricula']->valor_matricula()) ?? '',
            '[[VALOR_MENSALIDADE]]' => formatoValor($data['matricula']->valor_mensalidade()) ?? '',
            '[[VALOR_MENSALIDADE_EXTENSO]]' => valorExtenso($data['matricula']->valor_mensalidade()) ?? '',
            '[[PARCELAS_MENSALIDADE]]' => $data['matricula']->total_mensalidades() ?? '',
            '[[MES_INICIO_MENSALIDADE]]' => nomeMes($data['matricula']->ano_letivo->ano_letivo_mes_inicio_mensalidade).'/'.$data['matricula']->ano_letivo->ano_letivo_nome ?? '',
            '[[MES_TERMINO_MENSALIDADE]]' => nomeMes($data['matricula']->ano_letivo->ano_letivo_mes_termino_mensalidade).'/'.$data['matricula']->ano_letivo->ano_letivo_nome ?? '',
            '[[DIA_VENCIMENTO_MENSALIDADE]]' => $data['matricula']->ano_letivo->ano_letivo_dia_vencimento_mensalidade ?? '',
            '[[COBRAR_DENTRO_MES]]' => ($data['matricula']->ano_letivo->ano_letivo_cobrar_dentro_mes == 1 ? 'do mês corrente' : 'do mês posterior ao vencido' ) ?? '',
            #Financeiro
            '[[PORCENTAGEM_MULTA]]' => formatoValor($data['matricula']->ano_letivo->ano_letivo_porcentagem_multa) ?? '',
            '[[PORCENTAGEM_JUROS]]' => formatoValor($data['matricula']->ano_letivo->ano_letivo_porcentagem_juros) ?? '',
			//Tabelas
            '[[TABELA_HORARIOS_FUNCIONAMENTO]]' => $tabela_horarios_funcionamento,
            '[[TABELA_INFORME_PAGAMENTO]]' => $tabela_informe_pagamento,
			#configurações
            '[[QUEBRA_LINHA]]' => '<div class="quebrar-linha"></div>',
            '[[LOCAL]]' => escola()->get('escola_responsavel_municipio').'/'.escola()->get('escola_responsavel_uf'),
            '[[DATA_ATUAL_EXTENSO]]' => dataExtenso(date('Y-m-d')),
		);
	}
}

function generateRandomStr($length = 8) {
    $UpperStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $LowerStr = "abcdefghijklmnopqrstuvwxyz";
    $symbols = "0123456789";
    $characters = $symbols.''.$LowerStr.''.$UpperStr;
    $charactersLength = strlen($characters);
    $randomStr = null;
    for ($i = 0; $i < $length; $i++) {
        $randomStr .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomStr;
}

function diaSemana($data){
	
	return utf8_encode(strftime('%A', strtotime($data)));
}

function novaData($dataInicio, $dias, $dia_fixo = false)
{	
    $dia_data_inicio = explode('/', $dataInicio);

	if($dataInicio != ''){
		$data_inicio = converteData($dataInicio);
		$nova_data = date('d/m/Y', strtotime('+'.$dias.' days', strtotime($data_inicio)));
	}else{
		$nova_data = '';
	}

    if($dia_fixo){
        $dia_novo = explode('/', $nova_data);
        $nova_data = $dia_data_inicio[0].'/'.$dia_novo[1].'/'.$dia_novo[2];
    }
	
	return $nova_data;
}

function ValidaData($data){
	$data = explode("/","$data"); // fatia a string $dat em pedados, usando / como referência
	$d = $data[0];
	$m = $data[1];
	$y = $data[2];

	// verifica se a data é válida!
	// 1 = true (válida)
	// 0 = false (inválida)
	return $res = checkdate($m,$d,$y);
}

function formatoId($id, $campo){
    $formatoId = str_pad($id, $campo, '0', STR_PAD_LEFT);
    return $formatoId;
}

function diaAtrasado($d1, $d2){
	$data_inicial = $d1;
	$data_final = $d2;

	// Calcula a diferença em segundos entre as datas
	$diferenca = strtotime($data_final) - strtotime($data_inicial);

	//Calcula a diferença em dias
	$dias = floor($diferenca / (60 * 60 * 24));

	return $dias;
}

//Convertendo o formato da data para d/m/Y
function formatoDataCompleta($data)
{
	if($data){
		$data = explode("-", $data);
    	return $data[2].'/'.$data[1].'/'.$data[0];
	}            
}

function mostrarDia($data)
{
    if($data){
        $data = explode("/", $data);
        return $data[0];
    }            
}

//Convertendo o formato da data para d/m/Y
function formatoDataHoraCompleta($data)
{
	if($data){
		$dataHora = explode(" ", $data);
		$data = explode("-", $dataHora[0]);
    	return $data[2].'/'.$data[1].'/'.$data[0].' '.$dataHora[1];
	}            
}

function formatoDataHoraDividaSomenteData($data)
{
	if($data){
		$dataHora = explode(" ", $data);
		$data = explode("-", $dataHora[0]);
    	return $data[2].'/'.$data[1].'/'.$data[0];
	}            
}

function formatoDataHoraDividaSomenteHora($data)
{
	if($data){
		$dataHora = explode(" ", $data);
    	return $dataHora[1];
	}            
}

//Convertendo o formato da data para d/m/Y
function formatoData($data){
	if($data){
		$data = explode("-", $data);
    	return $data[2].'/'.$data[1].'/'.$data[0];
	}            
}
function formatoDia($data){
	if($data){
		$data = explode("-", $data);
    	return $data[2];
	}            
}

//Convertendo o formato da data para Y-m-d
function converteData($data){
    $atendimentos = DateTime::createFromFormat('d/m/Y', $data);
    return $atendimentos->format('Y-m-d');      
}

function dataExtenso($data){
	setlocale(LC_ALL, NULL);
	setlocale(LC_ALL, 'pt_BR');  
	return utf8_encode(strftime('%d', strtotime($data)).' de '.ucwords(strftime('%B', strtotime($data))).' de '.strftime('%Y', strtotime($data)));
}

function dataExtensoCompleta($data){
	setlocale(LC_ALL, NULL);
	setlocale(LC_ALL, 'pt_BR');  
	return utf8_encode(strftime('%d', strtotime($data)).' de '.ucwords(strftime('%B', strtotime($data))).' de '.strftime('%Y', strtotime($data))).', às '.date('H:i:s', strtotime($data));;
}

function nomeMes($mes){
	switch ($mes) {
		case '01': $mes = 'Janeiro'; break;
		case '02': $mes = 'Fevereiro'; break;
		case '03': $mes = 'Março'; break;
		case '04': $mes = 'Abril'; break;
		case '05': $mes = 'Maio'; break;
		case '06': $mes = 'Junho'; break;
		case '07': $mes = 'Julho'; break;
		case '08': $mes = 'Agosto'; break;
		case '09': $mes = 'Setembro'; break;
		case '10': $mes = 'Outubro'; break;
		case '11': $mes = 'Novembro'; break;
		case '12': $mes = 'Dezembro'; break;
	}

	return $mes;
}

function idadeAtual($data){
    date_default_timezone_set('America/Sao_Paulo');
    $agora = date('Y-m-d',time()+3);

    $dataInicialConv = date('Y-m-d H:i:s', strtotime($data));
    $dataFinalConv = $agora;
    $inicio = date('d/m/Y H:i:s', strtotime($dataInicialConv));
    $fim = date('d/m/Y H:i:s', strtotime($dataFinalConv));

    $inicio = DateTime::createFromFormat('d/m/Y H:i:s', $inicio);
    $fim = DateTime::createFromFormat('d/m/Y H:i:s', $fim);
    $intervalo = $inicio->diff($fim);

    return $intervalo->format('%Y');    
}

function idadeLetiva($data){
    date_default_timezone_set('America/Sao_Paulo');
    $agora = converteData(env('DIA_MES_LIMITE_IDADE_SERIE').'/'.ano_letivo_atual());

    $dataInicialConv = date('Y-m-d H:i:s', strtotime($data));
    $dataFinalConv = $agora;
    $inicio = date('d/m/Y H:i:s', strtotime($dataInicialConv));
    $fim = date('d/m/Y H:i:s', strtotime($dataFinalConv));

    $inicio = DateTime::createFromFormat('d/m/Y H:i:s', $inicio);
    $fim = DateTime::createFromFormat('d/m/Y H:i:s', $fim);
    $intervalo = $inicio->diff($fim);

    return $intervalo->format('%Y');    
}

function tempoServico($data){
    $agora = date('Y-m-d', time()+3);

    $dataInicialConv = date('Y-m-d H:i:s', strtotime($data));
    $dataFinalConv = $agora;
    $inicio = date('d/m/Y H:i:s', strtotime($dataInicialConv));
    $fim = date('d/m/Y H:i:s', strtotime($dataFinalConv));

    $inicio = DateTime::createFromFormat('d/m/Y H:i:s', $inicio);
    $fim = DateTime::createFromFormat('d/m/Y H:i:s', $fim);
    $intervalo = $inicio->diff($fim);

    return $intervalo;


    // if($parte == 'ano'){
    // 	return $intervalo->format('%Y');    
    // }
    // if($parte == 'mes'){
    // 	return $intervalo->format('%m');    
    // }
    // if($parte == 'dia'){
    // 	return $intervalo->format('%d');    
    // }    
}

function primeiroNome($string){
	if(!empty($string)){
		$string = htmlentities($string);

		$partes = explode(' ', $string);
		$primeiroNome = array_shift($partes);
		$ultimoNome = array_pop($partes);

		$string = $primeiroNome;
	}

	return $string;
}

function nomeSobrenome($string){
	if(!empty($string)){
		$string = htmlentities($string);

		$partes = explode(' ', $string);
		$primeiroNome = array_shift($partes);
		$ultimoNome = array_pop($partes);

		$string = $primeiroNome.' '.$ultimoNome;
	}

	return $string;
}

function removeAcentos($string){
	if(!empty($string)){
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		$string = preg_replace('/[~`\'^]/', null, $string);
	}

	return $string;
}

function abreviaNome(){
	if(!empty($this->dado['valor'])){
		$string = $this->dado['valor'];

		$pattern = '/ de | do | dos | da | das | e /i';
		$string = preg_replace($pattern,' ',$string);
		$string = explode(' ', trim($string));

		$nomes_meio = ' ';
		echo count($string);
		if(count($string) > 2){
			echo 'é maior';
			for($x = 1; $x < count($string) - 1; $x++){
				$nomes_meio .= $string[$x][0].'. ';
			}
		}

		$this->dado['valor'] = array_shift($string).$nomes_meio.array_pop($string);
	}

	return $this;
}

function tratar_nome($nome){
	if(!empty($nome)){
		$string = htmlentities($nome);

		$string = strtolower($string); // Converter o string todo para minúsculo
	    $string = explode(" ", $string); // Separa o string por espaços
	    $saida = '';

	    $contador = 1;
	    for ($i=0; $i < count($string); $i++) {
	        // Tratar cada palavra do string
	    	if ($string[$i] == "de" or $string[$i] == "da" or $string[$i] == "das" or $string[$i] == "e" or $string[$i] == "dos" or $string[$i] == "do") {
	            $saida .= $string[$i].' '; // Se a palavra estiver dentro das complementares mostrar toda em minúsculo
	        }else {
	            if(count($string) - 1 == $contador){
	        		$saida .= ucfirst($string[$i]); // Se for um nome, mostrar a primeira letra maiúscula
	        		$contador = 1;
	        	}else{
	        		$saida .= ucfirst($string[$i]).' '; // Se for um nome, mostrar a primeira letra maiúscula
	        		$contador++;
	        	} 
	        }
	    }

	    return trim($saida);
	}else{
		return trim($saida);
	}
}

function tratarNome($nome) {
	$saida = '';
	
    $nome = strtolower($nome); // Converter o nome todo para minúsculo
    $nome = explode(" ", $nome); // Separa o nome por espaços

    $contador = 1;
    for ($i=0; $i < count($nome); $i++) {

        // Tratar cada palavra do nome
        if ($nome[$i] == "de" or $nome[$i] == "da" or $nome[$i] == "e" or $nome[$i] == "dos" or $nome[$i] == "do") {
            $saida .= $nome[$i].' '; // Se a palavra estiver dentro das complementares mostrar toda em minúsculo
        }else {
        	if(count($nome) - 1 == $contador){
        		$saida .= ucfirst($nome[$i]); // Se for um nome, mostrar a primeira letra maiúscula
        		$contador = 1;
        	}else{
        		$saida .= ucfirst($nome[$i]).' '; // Se for um nome, mostrar a primeira letra maiúscula
        		$contador++;
        	} 
        }
    }

    return $saida;
}

function nomePessoaParte($nomePassado, $numeroParte){
	$nome = str_replace([' dos ', ' de ', ' da ', ' e '], ' ', $nomePassado);
	$nome = explode(' ', $nome);

	if($numeroParte > count($nome)){
		$numeroParte = count($nome);
	}

	$nomeRetorno = '';

	for ($i=0; $i < $numeroParte; $i++) { 
		$nomeRetorno .= $nome[$i].' ';
	}

	return rtrim($nomeRetorno);
}

function saldo_produto_entrada($produto){
	
	//Busca entrada
	$qtdes_entrada = DB::table('farm_entradas_item')->where('entrada_item_id', $produto)->get();
	$produto_qtde_entrada = 0;
	foreach ($qtdes_entrada as $qtde) {
		$produto_qtde_entrada += $qtde->entrada_item_quantidade;
	}

	//Busca saída
	$qtdes_saida = DB::table('farm_saidas_item')->where('saida_item_entrada_item_id', $produto)->get();
	$produto_qtde_saida = 0;
	foreach ($qtdes_saida as $qtde) {
		$produto_qtde_saida += $qtde->saida_item_quantidade;
	}

	return $produto_qtde_entrada - $produto_qtde_saida;
}

function formatoValor($valor, $casas = 2){
	return number_format($valor,$casas,",",".");
}

function converteValor($valor, $verifica = true) {
	$source = array('.', ',');
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $valor); //remove os pontos e substitui a virgula pelo ponto
	
	if($verifica){
		$valor = explode('.', $valor);
		if($valor[1] == '00'){
			return $valor[0];
		}else{
			return $valor[0].'.'.$valor[1];
		}
	}

	return $valor; //retorna o valor formatado para gravar no banco
}

function numeroExtenso($valor = 0, $maiusculas = false) {

	$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("", "", "mil", "milhões", "bilhões", "trilhões",
		"quatrilhões");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
		"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
		"sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
		"dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
		"sete", "oito", "nove");

	$z = 0;
	$rt = "";

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
				$ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : " ").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
				($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
		}

	if(!$maiusculas){
		return($rt ? $rt : "zero");
	} else {

		if ($rt) $rt=preg_replace(" /E/ "," e ",ucwords($rt));
		return trim((($rt) ? ($rt) : "Zero"));
	}
}

function valorExtenso($valor = 0, $maiusculas = false) {

	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
		"quatrilhões");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
		"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
		"sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
		"dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
		"sete", "oito", "nove");

	$z = 0;
	$rt = "";

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
				$ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : " ").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
				($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
		}

	if(!$maiusculas){
		return($rt ? $rt : "zero");
	} else {

		if ($rt) $rt=preg_replace(" /E/ "," e ",ucwords($rt));
		return (($rt) ? ($rt) : "Zero");
	}
}

/**
Retornar a senha com 10 caracteres como maiúsculas, minusculas, números e símbolos:

<?php
   echo gerar_senha(10, true, true, true, true);
?>
Retornar a senha com 8 caracteres como maiúsculas, minusculas e números:

<?php
   echo gerar_senha(8, true, true, true, false);
?>
Retornar a senha com 6 caracteres como maiúsculas e minusculas:

<?php
   echo gerar_senha(6, true, true, false, false);    
?>

**/
function gerarSenha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
	$ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
	$mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
	$nu = "0123456789"; // $nu contem os números
	$si = "!@#$%¨&*()_+="; // $si contem os símbolos
	$senha = "";

	if ($maiusculas){
		// se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
		$senha .= str_shuffle($ma);
	}
 
    if ($minusculas){
        // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($mi);
    }
 
    if ($numeros){
        // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($nu);
    }
 
    if ($simbolos){
        // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($si);
    }
 
    // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
    return substr(str_shuffle($senha),0,$tamanho);
}
