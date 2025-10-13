<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacao extends Model
{
    use HasFactory;

    protected $primaryKey = 'organizacao_id';

    protected $table = 'organizacoes';

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'documento_organizacao_id');
    }
}
