<?php

declare(strict_types = 1);

namespace App\Models;

use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organizacao_id',
        'must_change_password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'must_change_password' => 'boolean',
            'password'             => 'hashed',
            'is_admin'             => 'boolean',
        ];
    }

    public function organizacao()
    {
        return $this->belongsTo(Organizacao::class, 'organizacao_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'usuario_id');
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function audits()
    {
        return $this->hasMany(Audit::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    public function logins(): HasMany
    {
        return $this->hasMany(Access::class);
    }

    public function mustChangePassword(): bool
    {
        return $this->must_change_password ?? false;
    }

    public function markPasswordAsChanged(): void
    {
        $this->update(['must_change_password' => false]);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmailNotification());
    }

    public function vinculosOutrasTabelas(): bool
    {
        $modelsToCheck = [
            AgenteSaude::class,
            Atendimento::class,
            EquipeSaude::class,
            Paciente::class,
            Procedimento::class,
            Setor::class,
            Solicitacao::class,
            TipoProcedimento::class,
        ];

        $fieldsToCheck = ['created_user_id', 'updated_user_id'];

        foreach ($modelsToCheck as $modelClass) {
            $modelInstance = new $modelClass();
            $tableName     = $modelInstance->getTable();

            foreach ($fieldsToCheck as $field) {
                if (\Illuminate\Support\Facades\Schema::hasColumn($tableName, $field)) {
                    if ($modelInstance->where($field, $this->id)->exists()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
