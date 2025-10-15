<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Audit extends Model
{
    protected $table = 'audits';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'data'       => 'array',
        'created_at' => 'datetime',
    ];

    protected $guarded = [];

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('table', 'like', '%' . $val . '%')
            ->orWhere('event', 'like', '%' . $val . '%')
            ->orWhere('column', 'like', '%' . $val . '%')
            ->orWhere('name', 'like', '%' . $val . '%')
            ->orWhere('audits.created_at', 'like', '%' . $val . '%');
    }

    protected static function booted()
    {
        static::creating(fn (Audit $audit) => $audit->id = (string) Uuid::uuid4());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
