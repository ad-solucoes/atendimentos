<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $pacienteToDelete;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->pacienteToDelete = $id;
    }

    public function delete()
    {
        $paciente = Paciente::find($this->pacienteToDelete);

        if ($paciente) {
            $paciente->delete();

            session()->flash('message', 'Paciente excluído com sucesso!');
        } else {
            session()->flash('message', 'Paciente não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        $pacientes = Paciente::query()
            ->where('paciente_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.pacientes.listagem', [
            'pacientes' => $pacientes,
        ])->layout('layouts.admin', ['title' => 'Pacientes']);
    }
}
