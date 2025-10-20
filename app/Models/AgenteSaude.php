<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\AgenteSaudeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([AgenteSaudeObserver::class])]
class AgenteSaude extends Model
{
    use HasFactory;

    protected $table = 'agentes_saude';

    protected $primaryKey = 'agente_saude_id';

    public function equipe_saude()
    {
        return $this->belongsTo(EquipeSaude::class, 'agente_saude_equipe_saude_id', 'equipe_saude_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'paciente_agente_saude_id', 'agente_saude_id');
    }
}
