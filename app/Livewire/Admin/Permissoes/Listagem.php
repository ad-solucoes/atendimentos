<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Permissoes;

use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $permissaoToDelete;

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
        $this->confirmingDelete  = true;
        $this->permissaoToDelete = $id;
    }

    public function delete()
    {
        $permissao = Permission::find($this->permissaoToDelete);

        if ($permissao) {
            if ($permissao->delete()) {
                flash()->addSuccess('Permissão deletado com sucesso.', [], 'Sucesso!');
            } else {
                flash()->addError('Erro ao deletar usuário.', [], 'Opssss!');
            }
        } else {
            flash()->addWarning('Permissão não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $permissoes = Permission::where('name', 'like', "%{$this->searchTerm}%")
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.permissoes.listagem', ['permissoes' => $permissoes])
            ->layout('layouts.admin', ['title' => 'Permissões']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
