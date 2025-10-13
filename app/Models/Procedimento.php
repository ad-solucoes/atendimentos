<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Procedimento extends Model
{
    use HasFactory;

    protected $table = 'procedimentos';
    protected $primaryKey = 'procedimento_id';
    public $timestamps = true;

    protected $fillable = [
        'procedimento_tipo_id',
        'procedimento_nome',
        'procedimento_codigo',
        'procedimento_status',
        'created_user_id',
        'updated_user_id',
    ];

    /**
     * Um procedimento pertence a um tipo de procedimento
     */
    public function tipo_procedimento()
    {
        return $this->belongsTo(TipoProcedimento::class, 'procedimento_tipo_id', 'tipo_procedimento_id');
    }

    /**
     * Um procedimento pode estar em várias solicitações
     */
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'solicitacao_procedimento_id', 'procedimento_id');
    }
}
