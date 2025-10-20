<?php

declare(strict_types = 1);

namespace App\Models;

use App\Observers\MunicipioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([MunicipioObserver::class])]
class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $primaryKey = 'municipio_id';

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'municipio_estado_id', 'estado_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'paciente_municipio_id', 'municipio_id');
    }
}
