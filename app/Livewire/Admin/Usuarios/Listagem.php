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

    public $etiquetaToDelete;

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
        $this->etiquetaToDelete = $id;
    }

    public function delete()
    {
        $etiqueta = User::find($this->etiquetaToDelete);

        if ($etiqueta) {
            $etiqueta->delete();
        }
        $this->confirmingDelete = false;
        session()->flash('message', 'Usuario excluída com sucesso!');
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
