<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\PacienteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PacienteObserver::class])]
class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $primaryKey = 'paciente_id';

    public $timestamps = true;

    protected $fillable = [
        'paciente_agente_saude_id',
        'paciente_nome',
        'paciente_sexo',
        'paciente_data_nascimento',
        'paciente_nome_mae',
        'paciente_endereco',
        'paciente_contato',
        'paciente_cartao_sus',
        'paciente_cpf',
        'paciente_status',
        'created_user_id',
        'updated_user_id',
    ];

    protected $casts = [
        'paciente_data_nascimento' => 'date',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
    ];

    public function getPacienteIdadeAttribute()
    {
        if ($this->paciente_data_nascimento) {
            return idadeAtual($this->paciente_data_nascimento->format('Y-m-d'));
        }

        return null;
    }

    public function agente_saude()
    {
        return $this->belongsTo(AgenteSaude::class, 'paciente_agente_saude_id', 'agente_saude_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'paciente_municipio_id', 'municipio_id');
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'atendimento_paciente_id', 'paciente_id');
    }
}
