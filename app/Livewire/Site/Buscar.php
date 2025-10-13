<?php

declare(strict_types = 1);

namespace App\Livewire\Site;

use App\Models\Documento;
use App\Models\Tipo;
use App\Models\Organizacao;
use Livewire\Component;
use Livewire\WithPagination;

class Buscar extends Component
{
    use WithPagination;

    public $query = '';

    public $filter_organizacao = null;

    public $filter_tipo = null;

    public $ano = null;

    protected $updatesQueryString = ['query', 'filter_organizacao', 'filter_tipo', 'ano'];

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $perPage = 10;

        if (strlen($this->query) > 0) {
            // usando Scout/Meilisearch se estiver instalado
            try {
                $resultados = Documento::search($this->query)
                    ->where('documento_status', 'publicado')
                    ->paginate($perPage);
            } catch (\Throwable $e) {
                // fallback para DB search
                $q = Documento::query()->where('documento_status', 'publicado')
                    ->where(function ($sub) {
                        $sub->where('documento_titulo', 'like', '%' . $this->query . '%')
                            ->orWhere('documento_descricao', 'like', '%' . $this->query . '%')
                            ->orWhere('documento_texto_ocr', 'like', '%' . $this->query . '%')
                            ->orWhere('documento_numero_oficial', 'like', '%' . $this->query . '%');
                    });
                $resultados = $q->paginate($perPage);
            }
        } else {
            $q = Documento::query()->where('documento_status', 'publicado');

            if ($this->filter_organizacao) {
                $q->where('documento_organizacao_id', $this->filter_organizacao);
            }

            if ($this->filter_tipo) {
                $q->where('documento_tipo_id', $this->filter_tipo);
            }

            if ($this->ano) {
                $q->whereYear('documento_data_emissao', $this->ano);
            }
            $resultados = $q->orderBy('documento_data_emissao', 'desc')->paginate($perPage);
        }

        return view('livewire.site.buscar', [
            'resultados'       => $resultados,
            'organizacoes' => Organizacao::orderBy('organizacao_nome')->get(),
            'tipos'         => Tipo::orderBy('tipo_nome')->get(),
        ])->layout('layouts.site', ['titulo' => 'Buscar']);
    }
}
