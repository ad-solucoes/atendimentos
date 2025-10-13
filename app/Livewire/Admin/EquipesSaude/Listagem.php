<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\EquipesSaude;

use App\Models\EquipeSaude;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $equipeSaudeToDelete;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete    = true;
        $this->equipeSaudeToDelete = $id;
    }

    public function delete()
    {
        $equipe_saude = EquipeSaude::find($this->equipeSaudeToDelete);

        if ($equipe_saude) {
            $equipe_saude->delete();

            session()->flash('message', 'EquipeSaude excluído com sucesso!');
        } else {
            session()->flash('message', 'EquipeSaude não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        $equipes_saude = EquipeSaude::query()
            ->where('equipe_saude_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.equipes_saude.listagem', [
            'equipes_saude' => $equipes_saude,
        ])->layout('layouts.admin', ['title' => 'Equipes de Saúde']);
    }
}
