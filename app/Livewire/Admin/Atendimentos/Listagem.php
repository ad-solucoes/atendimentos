<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'atendimento_data';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    // Filtros gerais
    public $filtroDataInicial;

    public $filtroDataFinal;

    public $filtroNumeroAtendimento;

    public $filtroPrioridade;

    // Filtros do paciente
    public $filtroNome;

    public $filtroCpf;

    public $filtroSus;

    public $filtroMae;

    public $confirmingDelete = false;

    public $atendimentoToDelete;

    protected $paginationTheme = 'personalizado';

    public function updating($field)
    {
        if (str_starts_with($field, 'filtro') || $field === 'perPage') {
            Paginator::currentPageResolver(function () {
                return $this->currentPage;
            });
        }
    }

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
            if ($atendimento->solicitacoes()->exists()) {
                flash()->addWarning('Este atendimento não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($atendimento->delete()) {
                    flash()->addSuccess('Atendimento deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar atendimento.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Atendimento não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $atendimentos = Atendimento::query()->with('paciente')
            ->when($this->filtroDataInicial, fn ($q) => $q->whereDate('atendimento_data', '>=', $this->filtroDataInicial))
            ->when($this->filtroDataFinal, fn ($q) => $q->whereDate('atendimento_data', '<=', $this->filtroDataFinal))
            ->when($this->filtroNumeroAtendimento, fn ($q) => $q->where('atendimento_numero', 'like', "%{$this->filtroNumeroAtendimento}%"))
            ->when($this->filtroPrioridade, fn ($q) => $q->where('atendimento_prioridade', $this->filtroPrioridade))
            ->when($this->filtroNome, fn ($q) => $q->whereHas('paciente', fn ($p) => $p->where('paciente_nome', 'like', "%{$this->filtroNome}%")))
            ->when($this->filtroCpf, fn ($q) => $q->whereHas('paciente', fn ($p) => $p->where('paciente_cpf', 'like', "%{$this->filtroCpf}%")))
            ->when($this->filtroSus, fn ($q) => $q->whereHas('paciente', fn ($p) => $p->where('paciente_cartao_sus', 'like', "%{$this->filtroSus}%")))
            ->when($this->filtroMae, fn ($q) => $q->whereHas('paciente', fn ($p) => $p->where('paciente_nome_mae', 'like', "%{$this->filtroMae}%")))
            ->orderBy($this->sortBy, $this->sortDirection)
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
