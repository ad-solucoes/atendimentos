<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes';
    protected $primaryKey = 'solicitacao_id';
    public $incrementing = false;
    public $timestamps = true;

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
        'solicitacao_data'    => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    /**
     * Enum de status da solicitação
     */
    const STATUS_AGUARDANDO = 'Pendente';
    const STATUS_AGENDADO = 'Em andamento';
    const STATUS_MARCADO = 'Concluída';
    const STATUS_ENTREGUE = 'Entregue';
    const STATUS_CANCELADO = 'Cancelada';

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
        return match($this->solicitacao_status) {
            self::STATUS_AGUARDANDO => 'Aguardando',
            self::STATUS_AGENDADO => 'Agendado',
            self::STATUS_MARCADO => 'Marcado',
            self::STATUS_ENTREGUE => 'Entregue',
            self::STATUS_CANCELADO => 'Cancelado',
            default => '',
        };
    }

    /**
     * Retorna a cor do status para UI
     */
    public function status_cor()
    {
        return match($this->solicitacao_status) {
            self::STATUS_AGUARDANDO => 'warning',
            self::STATUS_AGENDADO => 'dark',
            self::STATUS_MARCADO => 'success',
            self::STATUS_ENTREGUE => 'primary',
            self::STATUS_CANCELADO => 'danger',
            default => 'secondary',
        };
    }

    public function movimentacoes()
    {
        return $this->hasMany(\App\Models\SolicitacaoMovimentacao::class, 'movimentacao_solicitacao_id', 'solicitacao_id')
            ->with('usuario')
            ->latest();
    }
}
