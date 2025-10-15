<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\Setor;
use App\Models\User;
use App\Notifications\WelcomeUserNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $user_id;

    public $name;

    public $email;

    public $is_admin = 0;

    public $setor_id;

    public $status = 1;

    public $setores;

    protected function rules()
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id, 'id'),
            ],
            'is_admin' => 'boolean',
            'setor_id' => 'required_if:is_admin,0',
            'status'   => 'required',
        ];
    }

    protected $messages = [
        'name.required'   => 'O campo "Nome do Usuário" é obrigatório.',
        'name.unique'     => 'Este nome do setor já está em uso.',
        'status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        if (! $this->is_admin) {
            $this->setores = Setor::where('setor_status', 1)->orderBy('setor_nome')->get();
        }

        if ($id) {
            $user           = User::find($id);
            $this->user_id  = $user->id;
            $this->name     = $user->name;
            $this->email    = $user->email;
            $this->setor_id = $user->setor_id;
            $this->is_admin = $user->is_admin ? 1 : 0;
            $this->status   = $user->status;
        }
    }

    public function save()
    {
        $this->validate();

        $senha = Str::random(8);

        if ($this->user_id) {
            $usuario = User::find($this->user_id);
        } else {
            $usuario           = new User();
            $usuario->password = Hash::make($senha);
            ;
            $usuario->must_change_password = 1;
        }

        $usuario->name     = $this->name;
        $usuario->email    = $this->email;
        $usuario->is_admin = $this->is_admin;
        $usuario->setor_id = $this->setor_id;
        $usuario->status   = $this->status;

        if ($usuario->save()) {
            if (! $this->user_id) {
                $usuario->notify(new WelcomeUserNotification($senha));
            }

            flash()->success('Usuário salvo com sucesso.', [], 'Sucesso!');
        } else {
            flash()->error('Erro ao salvar usuário.', [], 'Opssss!');
        }

        return redirect()->route('admin.usuarios.listagem');
    }

    public function render()
    {
        return view('livewire.admin.usuarios.formulario')->layout('layouts.admin', ['title' => $this->user_id == null ? 'Cadastrar Usuário' : 'Editar Usuário']);
    }
}
