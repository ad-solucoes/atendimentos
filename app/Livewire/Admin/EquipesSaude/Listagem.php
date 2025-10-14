<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\EquipesSaude;

use App\Models\EquipeSaude;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'equipe_saude_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $equipeSaudeToDelete;

    protected $paginationTheme = 'personalizado';

    public function sortByField($field)
    {
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        $this->sortBy        = $field;
    }

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
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $equipes_saude = EquipeSaude::query()
            ->where('equipe_saude_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.equipes_saude.listagem', [
            'equipes_saude' => $equipes_saude,
        ])->layout('layouts.admin', ['title' => 'Equipes de Saúde']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
