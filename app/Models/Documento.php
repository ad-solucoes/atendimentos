<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Documento extends Model
{
    use HasFactory;
    use Searchable;

    protected $primaryKey = 'documento_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->documento_id)) {
                $model->documento_id = (string) Str::uuid();
            }
        });
    }

    protected $casts = [
        'documento_data_emissao'    => 'date',
        'documento_data_publicacao' => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function arquivos()
    {
        return $this->hasMany(Arquivo::class, 'arquivo_documento_id', 'documento_id');
    }

    public function organizacao()
    {
        return $this->belongsTo(Organizacao::class, 'documento_organizacao_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'documento_tipo_id');
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'documento_etiquetas', 'de_documento_id', 'de_etiqueta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function toSearchableArray()
    {
        return [
            'documento_id'             => $this->documento_id,
            'documento_titulo'         => $this->documento_titulo,
            'documento_descricao'      => $this->documento_descricao,
            'documento_texto_ocr'      => $this->documento_texto_ocr,
            'documento_numero_oficial' => $this->documento_numero_oficial,
            'documento_organizacao'    => $this->organizacao?->organizacao_nome,
            'documento_etiquetas'      => $this->etiquetas?->pluck('etiqueta_nome')->toArray(),
            'documento_data_emissao'   => optional($this->documento_data_emissao)->toDateString(),
        ];
    }
}
