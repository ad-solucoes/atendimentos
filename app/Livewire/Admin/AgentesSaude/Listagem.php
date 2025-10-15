<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\AgentesSaude;

use App\Models\AgenteSaude;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'agente_saude_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $searchTerm = '';

    public $confirmingDelete = false;

    public $agenteSaudeToDelete;

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
        $this->agenteSaudeToDelete = $id;
    }

    public function delete()
    {
        $agente_saude = AgenteSaude::find($this->agenteSaudeToDelete);

        if ($agente_saude) {
            if ($agente_saude->pacientes()->exists()) {
                flash()->addWarning('Este agente de saúde não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($agente_saude->delete()) {
                    flash()->addSuccess('Agente de saúde deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar agente de saúde.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Agente de saúde não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $agentes_saude = AgenteSaude::with('equipe_saude')
            ->where('agente_saude_nome', 'like', "%{$this->searchTerm}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.agentes_saude.listagem', [
            'agentes_saude' => $agentes_saude,
        ])->layout('layouts.admin', ['title' => 'Agentes de Saúde']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
