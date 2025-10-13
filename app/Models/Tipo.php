<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;

    protected $primaryKey = 'tipo_id';

    protected $fillable = ['nome'];

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'documento_tipo_id');
    }
}
