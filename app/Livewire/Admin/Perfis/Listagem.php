<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Perfis;

use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $perfilToDelete;

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
        $this->confirmingDelete = true;
        $this->perfilToDelete   = $id;
    }

    public function delete()
    {
        $perfil = Role::find($this->perfilToDelete);

        if ($perfil) {
            if ($perfil->delete()) {
                flash()->addSuccess('Perfil deletado com sucesso.', [], 'Sucesso!');
            } else {
                flash()->addError('Erro ao deletar usuário.', [], 'Opssss!');
            }
        } else {
            flash()->addWarning('Perfil não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $perfis = Role::where('name', 'like', "%{$this->searchTerm}%")
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.perfis.listagem', ['perfis' => $perfis])
            ->layout('layouts.admin', ['title' => 'Perfils']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
