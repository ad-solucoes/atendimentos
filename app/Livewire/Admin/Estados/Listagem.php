<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Estados;

use App\Models\Estado;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'estado_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $estadoToDelete;

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
        $this->estadoToDelete = $id;
    }

    public function delete()
    {
        $estado = Estado::find($this->estadoToDelete);

        if ($estado) {
            if ($estado->municipios()->exists()) {
                flash()->addWarning('Este estado não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($estado->delete()) {
                    flash()->addSuccess('Estado deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar estado.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Estado não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $estados = Estado::where('estado_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.estados.listagem', [
            'estados' => $estados,
        ])->layout('layouts.admin', ['title' => 'Estados']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
