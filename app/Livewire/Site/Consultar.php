<?php

namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\Atendimento;
use Illuminate\Support\Facades\DB;

class Consultar extends Component
{
    public $numero_atendimento = '20251017001';
    public $cpf = '611.828.545-40';
    public $ano_nascimento = '1984';
    public $resultado = null;

    protected function rules()
    {
        return [
            'numero_atendimento' => 'required|string|min:3',
            'cpf' => 'required|cpf', // se você usa a rule brasileira 'cpf'
            'ano_nascimento' => 'required|digits:4|integer|min:1900|max:'.date('Y'),
        ];
    }

    protected $messages = [
        'numero_atendimento.required' => 'Informe o número do atendimento.',
        'cpf.required' => 'Informe o CPF.',
        'ano_nascimento.required' => 'Informe o ano de nascimento.',
        'ano_nascimento.digits' => 'O ano de nascimento deve ter 4 dígitos.',
    ];

    public function buscarAtendimento()
    {
        $this->validate();

        // Salva log de consulta
        DB::table('consultas_log')->insert([
            'ip_usuario' => request()->ip(),
            'cpf' => $this->cpf,
            'numero_atendimento' => $this->numero_atendimento,
            'consultado_em' => now(),
        ]);

        $this->resultado = Atendimento::with(['paciente', 'solicitacoes.procedimento.tipo_procedimento'])
            ->where('atendimento_numero', $this->numero_atendimento)
            ->whereHas('paciente', function ($q) {
                $q->where('paciente_cpf', $this->cpf)
                  ->whereYear('paciente_data_nascimento', $this->ano_nascimento);
            })
            ->first();

        if (!$this->resultado) {
            session()->flash('aviso', 'Nenhum atendimento encontrado com os dados informados.');
        }
    }

    public function render()
    {
        return view('livewire.site.consultar')
            ->layout('layouts.site', ['title' => 'Consultar Atendimento']);
    }
}
