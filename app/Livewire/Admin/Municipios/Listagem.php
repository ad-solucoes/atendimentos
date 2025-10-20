<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Municipios;

use App\Models\Municipio;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'municipio_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $municipioToDelete;

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
        $this->municipioToDelete = $id;
    }

    public function delete()
    {
        $municipio = Municipio::find($this->municipioToDelete);

        if ($municipio) {
            if ($municipio->pacientes()->exists()) {
                flash()->addWarning('Este municipio não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($municipio->delete()) {
                    flash()->addSuccess('Municipio deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar municipio.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Municipio não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $municipios = Municipio::with('estado')
            ->where('municipio_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.municipios.listagem', [
            'municipios' => $municipios,
        ])->layout('layouts.admin', ['title' => 'Municipios']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
