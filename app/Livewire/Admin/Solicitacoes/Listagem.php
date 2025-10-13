<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Solicitacao;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $solicitacaoToDelete;

    protected $paginationTheme = 'tailwind';

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
        $solicitacoes = Solicitacao::with('atendimento.paciente', 'procedimento.tipo_procedimento')
            ->where('solicitacao_data', 'like', "%{$this->search}%")
            ->orderByDesc('solicitacao_data')
            ->paginate(10);

        return view('livewire.admin.solicitacoes.listagem', [
            'solicitacoes' => $solicitacoes,
        ])->layout('layouts.admin', ['title' => 'Solicitações']);
    }
}
