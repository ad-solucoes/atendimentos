<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'paciente_cns',
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

    /**
     * Um paciente pertence a um agente de saúde
     */
    public function agente_saude()
    {
        return $this->belongsTo(AgenteSaude::class, 'paciente_agente_saude_id', 'agente_saude_id');
    }

    /**
     * Um paciente pode ter vários atendimentos
     */
    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'atendimento_paciente_id', 'paciente_id');
    }
}
