<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\TiposProcedimento;

use App\Models\TipoProcedimento;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $tipoProcedimentoToDelete;

    protected $paginationTheme = 'tailwind';

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
        $tipos_procedimento = TipoProcedimento::query()
            ->where('tipo_procedimento_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.tipos_procedimento.listagem', [
            'tipos_procedimento' => $tipos_procedimento,
        ])->layout('layouts.admin', ['title' => 'Tipo de Procedimentos']);
    }
}
