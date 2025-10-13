<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\AgentesSaude;

use App\Models\AgenteSaude;
use App\Models\Arquivo;
use App\Models\EquipeSaude;
use App\Models\Tipo;
use App\Models\Organizacao;
use App\Models\Etiqueta;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public $agente_saude_id;

    public $agente_saude_nome;
    public $agente_saude_equipe_saude_id;

    public $equipes_saude;

    protected $rules = [
        'agente_saude_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        $this->equipes_saude = EquipeSaude::orderBy('equipe_saude_nome')->get();

        if ($id) {
            $doc = AgenteSaude::find($id);

            if ($doc) {
                $this->agente_saude_id      = $doc->agente_saude_id;
                $this->agente_saude_nome            = $doc->agente_saude_nome;
                $this->agente_saude_equipe_saude_id            = $doc->agente_saude_equipe_saude_id;
            }
        }
    }

    public function save()
    {
        $this->validate();

        AgenteSaude::updateOrCreate(
            ['agente_saude_id' => $this->agente_saude_id],
            [
                'agente_saude_nome'            => $this->agente_saude_nome,
                'agente_saude_equipe_saude_id'            => $this->agente_saude_equipe_saude_id,
            ]
        );

        session()->flash('message', 'AgenteSaude salvo com sucesso!');

        return redirect()->route('admin.agentes_saude.listagem');
    }

    public function render()
    {
        return view('livewire.admin.agentes_saude.formulario')
            ->layout('layouts.admin', ['title' => $this->agente_saude_id == null ? 'Cadastrar Agente de Saúde' : 'Editar Agente de Saúde']);
    }
}
