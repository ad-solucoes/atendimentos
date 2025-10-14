<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Procedimentos;

use App\Models\Procedimento;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'procedimento_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $procedimentoToDelete;

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
        $this->confirmingDelete     = true;
        $this->procedimentoToDelete = $id;
    }

    public function delete()
    {
        $procedimento = Procedimento::find($this->procedimentoToDelete);

        if ($procedimento) {
            $procedimento->delete();

            session()->flash('message', 'Procedimento excluído com sucesso!');
        } else {
            session()->flash('message', 'Procedimento não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $procedimentos = Procedimento::with('tipo_procedimento')
            ->where('procedimento_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.procedimentos.listagem', [
            'procedimentos' => $procedimentos,
        ])->layout('layouts.admin', ['title' => 'Procedimentos']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
