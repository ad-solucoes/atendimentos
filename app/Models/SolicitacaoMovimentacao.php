<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoMovimentacao extends Model
{
    protected $table = 'solicitacao_movimentacoes';
    protected $primaryKey = 'movimentacao_id';
    public $timestamps = true;

    protected $fillable = [
        'movimentacao_solicitacao_id',
        'movimentacao_status',
        'movimentacao_destinatario_tipo', // paciente, acs, equipe
        'movimentacao_destinatario_id',
        'movimentacao_observacao',
        'movimentacao_data',
        'movimentacao_usuario_id',
    ];

    protected $casts = [
        'movimentacao_data'    => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'movimentacao_usuario_id');
    }

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'movimentacao_solicitacao_id');
    }
}
