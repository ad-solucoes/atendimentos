<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\Paciente;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
    use WithPagination;

    public $sortBy = 'paciente_nome';

    public $sortDirection = 'asc';

    public $perPage = '10';

    public $currentPage = 1;

    public $filtroNome = '';

    public $filtroCpf = '';

    public $filtroSus = '';

    public $filtroMae = '';

    public $confirmingDelete = false;

    public $pacienteToDelete;

    protected $paginationTheme = 'personalizado';

    public function updating($field)
    {
        if (str_starts_with($field, 'filtro') || $field === 'perPage') {
            Paginator::currentPageResolver(function () {
                return $this->currentPage;
            });
        }
    }

    public function sortByField($field)
    {
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        $this->sortBy        = $field;
    }

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
        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });

        $paciente = Paciente::find($this->pacienteToDelete);

        if ($paciente) {
            if ($paciente->atendimentos()->exists()) {
                flash()->addWarning('Este paciente não pode ser deletado.', [], 'Alerta!');
            } else {
                if ($paciente->delete()) {
                    flash()->addSuccess('Paciente deletado com sucesso.', [], 'Sucesso!');
                } else {
                    flash()->addError('Erro ao deletar paciente.', [], 'Opssss!');
                }
            }
        } else {
            flash()->addWarning('Paciente não encontrado.', [], 'Alerta!');
        }

        $this->confirmingDelete = false;
    }

    public function render()
    {
        $query = Paciente::query();

        if ($this->filtroNome) {
            $query->where('paciente_nome', 'like', "%{$this->filtroNome}%");
        }

        if ($this->filtroCpf) {
            // Remove máscara antes de buscar
            $cpfLimpo = preg_replace('/[^0-9]/', '', $this->filtroCpf);
            $query->whereRaw("REPLACE(REPLACE(REPLACE(paciente_cpf, '.', ''), '-', ''), ' ', '') LIKE ?", ["%{$cpfLimpo}%"]);
        }

        if ($this->filtroSus) {
            $susLimpo = preg_replace('/[^0-9]/', '', $this->filtroSus);
            $query->whereRaw("REPLACE(REPLACE(paciente_cartao_sus, ' ', ''), '-', '') LIKE ?", ["%{$susLimpo}%"]);
        }

        if ($this->filtroMae) {
            $query->where('paciente_nome_mae', 'like', "%{$this->filtroMae}%");
        }

        if (! $this->filtroNome && ! $this->filtroCpf && ! $this->filtroSus && ! $this->filtroMae) {
            $pacientes = $query->orderByDesc('created_at')->paginate($this->perPage);
        } else {
            $pacientes = $query->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);
        }

        return view('livewire.admin.pacientes.listagem', [
            'pacientes' => $pacientes,
        ])->layout('layouts.admin', ['title' => 'Pacientes']);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }
}
