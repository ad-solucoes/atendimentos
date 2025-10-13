<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenteSaude extends Model
{
    use HasFactory;

    protected $table = 'agentes_saude';

    protected $primaryKey = 'agente_saude_id';

    public $timestamps = true;

    protected $fillable = [
        'agente_saude_equipe_saude_id',
        'agente_saude_nome',
        'created_user_id',
    ];

    /**
     * Relacionamento: um agente pertence a uma equipe de saúde
     */
    public function equipe_saude()
    {
        return $this->belongsTo(EquipeSaude::class, 'agente_saude_equipe_saude_id', 'equipe_saude_id');
    }

    /**
     * Relacionamento: um agente tem vários pacientes
     */
    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'paciente_agente_saude_id', 'agente_saude_id');
    }
}
