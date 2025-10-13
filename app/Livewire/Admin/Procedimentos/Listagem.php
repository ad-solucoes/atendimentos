<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Procedimentos;

use App\Models\Procedimento;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $procedimentoToDelete;

    protected $paginationTheme = 'tailwind';

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
        $procedimentos = Procedimento::with('tipo_procedimento')
            ->where('procedimento_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.procedimentos.listagem', [
            'procedimentos' => $procedimentos,
        ])->layout('layouts.admin', ['title' => 'Procedimentos']);
    }
}
