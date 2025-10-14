<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\TiposProcedimento;

use App\Models\TipoProcedimento;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'tipo_procedimento_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $tipoProcedimentoToDelete;

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
        $this->confirmingDelete         = true;
        $this->tipoProcedimentoToDelete = $id;
    }

    public function delete()
    {
        $tipo_procedimento = TipoProcedimento::find($this->tipoProcedimentoToDelete);

        if ($tipo_procedimento) {
            $tipo_procedimento->delete();

            session()->flash('message', 'TipoProcedimento excluído com sucesso!');
        } else {
            session()->flash('message', 'TipoProcedimento não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $tipos_procedimento = TipoProcedimento::query()
            ->where('tipo_procedimento_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.tipos_procedimento.listagem', [
            'tipos_procedimento' => $tipos_procedimento,
        ])->layout('layouts.admin', ['title' => 'Tipo de Procedimentos']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
