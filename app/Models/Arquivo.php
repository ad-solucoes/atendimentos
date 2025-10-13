<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Arquivo extends Model
{
    use HasFactory;

    protected $primaryKey = 'arquivo_id';

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'arquivo_documento_id', 'id');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->arquivo_caminho_armazenamento);
    }
}
