<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'atendimento_data';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $atendimentoToDelete;

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
        $this->atendimentoToDelete = $id;
    }

    public function delete()
    {
        $atendimento = Atendimento::find($this->atendimentoToDelete);

        if ($atendimento) {
            $atendimento->delete();

            session()->flash('message', 'Atendimento excluído com sucesso!');
        } else {
            session()->flash('message', 'Atendimento não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $atendimentos = Atendimento::with('paciente')
            ->where('atendimento_data', 'like', "%{$this->searchTerm}%")
            ->orderByDesc($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.atendimentos.listagem', [
            'atendimentos' => $atendimentos,
        ])->layout('layouts.admin', ['title' => 'Atendimentos']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
