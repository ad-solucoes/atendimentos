<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\SolicitacaoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SolicitacaoObserver::class])]
class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes';

    protected $primaryKey = 'solicitacao_id';

    protected $fillable = [
        'solicitacao_atendimento_id',
        'solicitacao_procedimento_id',
        'solicitacao_localizacao_atual_id',
        'solicitacao_numero',
        'solicitacao_data',
        'solicitacao_status',
        'created_user_id',
        'updated_user_id',
    ];

    protected $casts = [
        'solicitacao_data' => 'date',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Enum de status da solicitação
     */
    public const STATUS_AGUARDANDO = 'Pendente';
    public const STATUS_AGENDADO   = 'Em andamento';
    public const STATUS_MARCADO    = 'Concluída';
    public const STATUS_ENTREGUE   = 'Entregue';
    public const STATUS_CANCELADO  = 'Cancelada';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitacao) {
            $solicitacao->solicitacao_numero = self::gerarNumeroSolicitacao();
        });
    }

    public static function gerarNumeroSolicitacao()
    {
        $anoAtual = date('Y');

        // Busca última solicitação do ano
        $ultimo = self::whereYear('solicitacao_data', $anoAtual)
            ->orderBy('solicitacao_numero', 'desc')
            ->first();

        if ($ultimo) {
            // Pega os últimos 6 dígitos e incrementa
            $numero = intval(substr($ultimo->solicitacao_numero, -6)) + 1;
        } else {
            $numero = 1;
        }

        // Monta o número final no formato AAAAXXXXXX
        return $anoAtual . formatoId($numero, 6);
    }

    /**
     * Relacionamentos
     */
    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'solicitacao_atendimento_id', 'atendimento_id');
    }

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'solicitacao_procedimento_id', 'procedimento_id');
    }

    public function localizacao_atual()
    {
        return $this->belongsTo(Setor::class, 'solicitacao_localizacao_atual_id', 'setor_id');
    }

    /**
     * Retorna a descrição do status
     */
    public function status_descricao()
    {
        return match ($this->solicitacao_status) {
            self::STATUS_AGUARDANDO => 'Aguardando',
            self::STATUS_AGENDADO   => 'Agendado',
            self::STATUS_MARCADO    => 'Marcado',
            self::STATUS_ENTREGUE   => 'Entregue',
            self::STATUS_CANCELADO  => 'Cancelado',
            default                 => '',
        };
    }

    /**
     * Retorna a cor do status para UI
     */
    public function status_cor()
    {
        return match ($this->solicitacao_status) {
            self::STATUS_AGUARDANDO => 'warning',
            self::STATUS_AGENDADO   => 'dark',
            self::STATUS_MARCADO    => 'success',
            self::STATUS_ENTREGUE   => 'primary',
            self::STATUS_CANCELADO  => 'danger',
            default                 => 'secondary',
        };
    }

    public function movimentacoes()
    {
        return $this->hasMany(SolicitacaoMovimentacao::class, 'movimentacao_solicitacao_id', 'solicitacao_id')
            ->with('usuario')
            ->latest();
    }

    public function mensagemParaPaciente()
    {
        $paciente = $this->atendimento->paciente; // relacionamento solicitacao->paciente

        $mensagem = "*Sistema Atendimentos SMS*\n";
        $mensagem .= "Secretaria Municipal de Saúde\n";
        $mensagem .= "Barra de Santo Antônio/AL\n\n";

        $mensagem .= "Olá, *{$paciente->paciente_nome}*!\n\n";

        $mensagem .= "Solicitação: *{$this->solicitacao_numero}*\n";
        $mensagem .= "Data: *{$this->solicitacao_data->format('d/m/Y')}*\n";
        $mensagem .= "Status atual: *" . strtoupper($this->solicitacao_status) . "*\n\n";

        switch ($this->solicitacao_status) {
            case 'marcado':
                $mensagem .= "Sua solicitação foi marcada. Você pode retirar a documentação na secretaria ou com seu agente de saúde.\n\n";

                break;

            case 'devolvido':
            case 'cancelado':
                $mensagem .= "Sua solicitação foi {strtoupper($this->solicitacao_status)}. Por favor, compareça à secretaria para resolver sobre sua solicitação.\n\n";

                break;

            case 'aguardando':
            case 'agendado':
                $mensagem .= "Estamos fazendo todo o esforço para que sua solicitação seja processada e marcada o mais breve possível.\n\n";

                break;

            default:
                $mensagem .= "Para maiores informações sobre o andamento da solicitação, favor acessar: https://atendimentos.saudebsa.com.br/consultar\n\n";

                break;
        }

        $mensagem .= "Atenciosamente,\n\nSecretaria Municipal de Saúde";

        return $mensagem;
    }
}
