<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\EquipesSaude;

use App\Models\EquipeSaude;
use Livewire\Component;

class Detalhes extends Component
{
    public $equipe_saude;

    public function mount($id = null)
    {
        $this->equipe_saude = EquipeSaude::find($id);
    }

    public function render()
    {
        return view('livewire.admin.equipes_saude.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Equipe de Saúde']);
    }
}
