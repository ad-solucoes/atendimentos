<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoProcedimento extends Model
{
    use HasFactory;

    protected $table = 'tipos_procedimento';
    protected $primaryKey = 'tipo_procedimento_id';
    public $timestamps = true;

    protected $fillable = [
        'tipo_procedimento_nome',
        'tipo_procedimento_status',
        'created_user_id',
        'updated_user_id',
    ];

    /**
     * Um tipo de procedimento possui vários procedimentos
     */
    public function procedimentos()
    {
        return $this->hasMany(Procedimento::class, 'procedimento_tipo_id', 'tipo_procedimento_id');
    }
}
