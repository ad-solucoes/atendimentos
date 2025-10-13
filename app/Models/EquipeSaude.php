<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipeSaude extends Model
{
    use HasFactory;

    protected $table = 'equipes_saude';
    protected $primaryKey = 'equipe_saude_id';
    public $timestamps = true;

    protected $fillable = [
        'equipe_saude_nome',
        'equipe_saude_status',
        'created_user_id',
        'updated_user_id',
    ];

    /**
     * Uma equipe de saúde possui vários agentes de saúde
     */
    public function agentes_saude()
    {
        return $this->hasMany(AgenteSaude::class, 'agente_saude_equipe_saude_id', 'equipe_saude_id');
    }
}
