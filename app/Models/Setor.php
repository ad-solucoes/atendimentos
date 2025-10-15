<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\SetorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SetorObserver::class])]
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

    public function usuarios()
    {
        return $this->hasMany(User::class, 'setor_id', 'setor_id');
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'solicitacao_localizacao_atual_id', 'setor_id');
    }
}
