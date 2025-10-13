<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Setores;

use App\Models\Setor;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $setorToDelete;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->setorToDelete = $id;
    }

    public function delete()
    {
        $setor = Setor::find($this->setorToDelete);

        if ($setor) {
            $setor->delete();

            session()->flash('message', 'Setor excluído com sucesso!');
        } else {
            session()->flash('message', 'Setor não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        $setores = Setor::query()
            ->where('setor_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.setores.listagem', [
            'setores' => $setores,
        ])->layout('layouts.admin', ['title' => 'Gerenciar Setores']);
    }
}
