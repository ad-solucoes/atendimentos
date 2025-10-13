<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\AgentesSaude;

use App\Models\AgenteSaude;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDelete = false;

    public $agenteSaudeToDelete;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->agenteSaudeToDelete = $id;
    }

    public function delete()
    {
        $agente_saude = AgenteSaude::find($this->agenteSaudeToDelete);

        if ($agente_saude) {
            $agente_saude->delete();

            session()->flash('message', 'AgenteSaude excluído com sucesso!');
        } else {
            session()->flash('message', 'AgenteSaude não encontrado.');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        $agentes_saude = AgenteSaude::with('equipe_saude')
            ->where('agente_saude_nome', 'like', "%{$this->search}%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.agentes_saude.listagem', [
            'agentes_saude' => $agentes_saude,
        ])->layout('layouts.admin', ['title' => 'Gerenciar Agentes de Saúde']);
    }
}
