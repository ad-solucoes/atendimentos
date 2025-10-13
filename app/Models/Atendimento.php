<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    protected $table = 'atendimentos';

    protected $primaryKey = 'atendimento_id';

    public $timestamps = true;

    protected $fillable = [
        'atendimento_paciente_id',
        'atendimento_numero',
        'atendimento_data',
        'atendimento_prioridade',
        'atendimento_observacao',
        'atendimento_status',
        'created_user_id',
        'updated_user_id',
    ];

    protected $casts = [
        'atendimento_data' => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Um atendimento pertence a um paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'atendimento_paciente_id', 'paciente_id');
    }

    /**
     * Um atendimento pode ter várias solicitações
     */
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'solicitacao_atendimento_id', 'atendimento_id');
    }

    /**
     * Gera automaticamente o número do atendimento no formato AAAAMMDD### (ex: 20251012001)
     */
    public static function gerarNumeroAtendimento()
    {
        $dataHoje  = now()->format('Ymd');
        $sequencia = self::whereDate('created_at', now()->toDateString())->count() + 1;

        return $dataHoje . str_pad($sequencia, 3, '0', STR_PAD_LEFT);
    }
}
