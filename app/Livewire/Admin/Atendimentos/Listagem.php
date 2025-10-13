<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $atendimentoToDelete;

    protected $paginationTheme = 'tailwind';

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
        $atendimentos = Atendimento::with('paciente')
            ->where('atendimento_data', 'like', "%{$this->search}%")
            ->orderByDesc('atendimento_data')
            ->paginate(10);

        return view('livewire.admin.atendimentos.listagem', [
            'atendimentos' => $atendimentos,
        ])->layout('layouts.admin', ['title' => 'Atendimentos']);
    }
}
