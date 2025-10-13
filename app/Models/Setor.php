<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';

    protected $primaryKey = 'setor_id';

    public $timestamps = true;

    protected $fillable = [
        'setor_nome',
        'setor_status',
        'created_user_id',
        'updated_user_id',
    ];

    /**
     * Um setor pode ter várias solicitações
     */
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'solicitacao_setor_id', 'setor_id');
    }
}
