<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $usuarioToDelete;

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
        $this->usuarioToDelete  = $id;
    }

    public function delete()
    {
        $usuario = User::find($this->usuarioToDelete);

        if ($usuario) {
            if ($usuario->vinculosOutrasTabelas()) {
                flash()->addWarning('Este usuário não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($usuario->delete()) {
                    flash()->addSuccess('Usuário deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar usuário.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Usuário não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $usuarios = User::where('name', 'like', "%{$this->searchTerm}%")
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.usuarios.listagem', ['usuarios' => $usuarios])
            ->layout('layouts.admin', ['title' => 'Usuários']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
