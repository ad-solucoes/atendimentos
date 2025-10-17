<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\AtendimentoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([AtendimentoObserver::class])]
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($atendimento) {
            $atendimento->atendimento_numero = self::gerarNumeroAtendimento();
        });
    }

    // Função para gerar o número
    public static function gerarNumeroAtendimento()
    {
        $hoje = date('Y-m-d');

        // Busca último atendimento do dia
        $ultimo = self::whereDate('atendimento_data', $hoje)
            ->orderBy('atendimento_data', 'desc')
            ->first();

        if ($ultimo) {
            // Pega os últimos 3 dígitos e incrementa
            $numero = intval(substr($ultimo->atendimento_numero, -3)) + 1;
        } else {
            $numero = 1;
        }

        // Monta o número final no formato AAAAMMDDXXX
        return date('Ymd') . formatoId($numero, 3);
    }

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
}
