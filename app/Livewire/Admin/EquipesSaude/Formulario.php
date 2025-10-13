<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\EquipesSaude;

use App\Models\EquipeSaude;
use App\Models\Arquivo;
use App\Models\Tipo;
use App\Models\Organizacao;
use App\Models\Etiqueta;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public $equipe_saude_id;

    public $equipe_saude_nome;

    protected $rules = [
        'equipe_saude_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = EquipeSaude::find($id);

            if ($doc) {
                $this->equipe_saude_id      = $doc->equipe_saude_id;
                $this->equipe_saude_nome            = $doc->equipe_saude_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        EquipeSaude::updateOrCreate(
            ['equipe_saude_id' => $this->equipe_saude_id],
            [
                'equipe_saude_nome'            => $this->equipe_saude_nome,
            ]
        );

        session()->flash('message', 'EquipeSaude salvo com sucesso!');

        return redirect()->route('admin.equipes_saude.listagem');
    }

    public function render()
    {
        return view('livewire.admin.equipes_saude.formulario')
            ->layout('layouts.admin', ['title' => $this->equipe_saude_id == null ? 'Cadastrar Equipe de Saúude' : 'Editar Equipe de Saúde']);
    }
}
