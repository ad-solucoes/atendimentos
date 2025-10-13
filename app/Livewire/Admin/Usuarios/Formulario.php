<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\Setor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Formulario extends Component
{
    public $user_id;

    public $name;

    public $email;
    
    public $is_admin = 0;

    public $setor_id;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email',
        'is_admin' => 'boolean',
        'setor_id' => 'required_if:is_admin,0',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $user           = User::find($id);
            $this->user_id  = $user->id;
            $this->name     = $user->name;
            $this->email    = $user->email;
            $this->setor_id    = $user->setor_id;
            $this->is_admin = $user->is_admin ? 1 : 0;
        }
    }

    public function save()
    {
        $data = $this->validate();

        if(!$this->user_id){
            // $senha = Str::random(10);
            // $data['password'] = $senha;
            $data['password'] = Hash::make('password');
        }        

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', 'Usuário salvo com sucesso!');

        return redirect()->route('admin.usuarios.listagem');
    }

    public function render()
    {
        if(!$this->is_admin){
            $setores = Setor::all();
        }

        return view('livewire.admin.usuarios.formulario', [
            'setores' => $setores ?? []
        ])->layout('layouts.admin', ['title' => $this->user_id == null ? 'Cadastrar Usuário' : 'Editar Usuário']);
    }
}
