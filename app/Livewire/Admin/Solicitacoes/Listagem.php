<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Solicitacao;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'solicitacao_data';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $filtroDataInicial;

    public $filtroDataFinal;

    public $filtroNumeroSolicitacao;

    public $filtroPrioridade;

    public $filtroTipoProcedimento;

    public $filtroProcedimento;

    public $filtroNome;

    public $filtroCpf;

    public $filtroSus;

    public $filtroMae;

    public $filtroStatus;

    public $confirmingDelete = false;

    public $solicitacaoToDelete;

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
        $this->solicitacaoToDelete = $id;
    }

    public function delete()
    {
        $solicitacao = Solicitacao::find($this->solicitacaoToDelete);

        if ($solicitacao) {
            $solicitacao->delete();

            session()->flash('message', 'Solicitacao excluído com sucesso!');
        } else {
            session()->flash('message', 'Solicitacao não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $solicitacoes = Solicitacao::with([
            'atendimento.paciente',
            'procedimento.tipo_procedimento',
        ])
            ->when($this->filtroDataInicial, function ($query) {
                $query->whereDate('solicitacao_data', '>=', $this->filtroDataInicial);
            })
            ->when($this->filtroDataFinal, function ($query) {
                $query->whereDate('solicitacao_data', '<=', $this->filtroDataFinal);
            })
            ->when($this->filtroNumeroSolicitacao, function ($query) {
                $query->where('solicitacao_numero', 'like', "%{$this->filtroNumeroSolicitacao}%");
            })
            ->when($this->filtroPrioridade, function ($query) {
                $query->where('solicitacao_prioridade', $this->filtroPrioridade);
            })
            ->when($this->filtroTipoProcedimento, function ($query) {
                $query->whereHas('procedimento.tipo_procedimento', function ($q) {
                    $q->where('procedimento_tipo_nome', 'like', "%{$this->filtroTipoProcedimento}%");
                });
            })
            ->when($this->filtroProcedimento, function ($query) {
                $query->whereHas('procedimento', function ($q) {
                    $q->where('procedimento_nome', 'like', "%{$this->filtroProcedimento}%");
                });
            })
            ->when($this->filtroNome, function ($query) {
                $query->whereHas('atendimento.paciente', function ($q) {
                    $q->where('paciente_nome', 'like', "%{$this->filtroNome}%");
                });
            })
            ->when($this->filtroCpf, function ($query) {
                $query->whereHas('atendimento.paciente', function ($q) {
                    $q->where('paciente_cpf', 'like', "%{$this->filtroCpf}%");
                });
            })
            ->when($this->filtroSus, function ($query) {
                $query->whereHas('atendimento.paciente', function ($q) {
                    $q->where('paciente_cartao_sus', 'like', "%{$this->filtroSus}%");
                });
            })
            ->when($this->filtroMae, function ($query) {
                $query->whereHas('atendimento.paciente', function ($q) {
                    $q->where('paciente_mae', 'like', "%{$this->filtroMae}%");
                });
            })
            ->when($this->filtroStatus, fn ($query) => $query->where('solicitacao_status', $this->filtroStatus))
            ->orderByDesc($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.solicitacoes.listagem', [
            'solicitacoes' => $solicitacoes,
        ])->layout('layouts.admin', ['title' => 'Solicitações']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
