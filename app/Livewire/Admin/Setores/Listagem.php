<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Setores;

use App\Models\Setor;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'setor_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $setorToDelete;

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
        $this->setorToDelete    = $id;
    }

    public function delete()
    {
        $setor = Setor::find($this->setorToDelete);

        if ($setor) {
            $setor->delete();

            session()->flash('message', 'Setor excluído com sucesso!');
        } else {
            session()->flash('message', 'Setor não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $setores = Setor::query()
            ->where('setor_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.setores.listagem', [
            'setores' => $setores,
        ])->layout('layouts.admin', ['title' => 'Setores']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
