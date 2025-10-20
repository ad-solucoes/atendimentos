<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\EquipeSaudeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EquipeSaudeObserver::class])]
class EquipeSaude extends Model
{
    use HasFactory;

    protected $table = 'equipes_saude';

    protected $primaryKey = 'equipe_saude_id';
    
    public function agentes_saude()
    {
        return $this->hasMany(AgenteSaude::class, 'agente_saude_equipe_saude_id', 'equipe_saude_id');
    }
}
