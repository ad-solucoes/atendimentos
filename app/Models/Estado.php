<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\EstadoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EstadoObserver::class])]
class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $primaryKey = 'estado_id';

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'municipio_estado_id', 'estado_id');
    }
}
