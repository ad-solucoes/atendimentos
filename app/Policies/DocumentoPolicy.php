<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class DocumentoPolicy
{
    public function viewAny(User $user)
    {
        // Admin vê tudo
        return true;
    }

    public function view(User $user, Documento $documento)
    {
        return $user->isAdmin() || $user->organizacao_id === $documento->documento_organizacao_id;
    }

    public function create(User $user)
    {
        return true; // Ambos podem criar
    }

    public function update(User $user, Documento $documento)
    {
        return $user->isAdmin() || $user->organizacao_id === $documento->documento_organizacao_id;
    }

    public function delete(User $user, Documento $documento)
    {
        return $user->isAdmin() || $user->organizacao_id === $documento->documento_organizacao_id;
    }
}
