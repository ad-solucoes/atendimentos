<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $primaryKey = 'etiqueta_id';

    public function documentos()
    {
        return $this->belongsToMany(Documento::class, 'documento_etiquetas', 'etiqueta_id', 'documento_id');
    }
}
