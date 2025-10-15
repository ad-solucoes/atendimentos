<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'logs';

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
    ];

    public $messages = [
        'ano_letivo_nome.required' => 'O campo "Nome da Ação" é obrigatório',
        'ano_letivo_nome.unique'   => 'O valor informado já está em uso',
    ];

    public static function logMessage($message)
    {
        return (new static())->create([
            'message'     => $message,
            'date'        => now(),
            'action'      => request()->getMethod(),
            'description' => request()->getPathInfo(),
            'user_id'     => auth()->user()->id ?? null,
            'ip_origin'   => request()->ip(),
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
