<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $etiquetaToDelete;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->etiquetaToDelete      = $id;
    }

    public function delete()
    {
        $etiqueta = User::find($this->etiquetaToDelete);

        if ($etiqueta) {
            $etiqueta->delete();
        }
        $this->confirmingDelete = false;
        session()->flash('message', 'Usuario excluída com sucesso!');
    }

    public function render()
    {
        $usuarios = User::where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.usuarios.listagem', ['usuarios' => $usuarios])
            ->layout('layouts.admin', ['title' => 'Gerenciar Usuarios']);
    }
}
