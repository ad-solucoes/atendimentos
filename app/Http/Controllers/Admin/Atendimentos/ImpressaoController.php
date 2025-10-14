<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin\Atendimentos;

use App\Http\Controllers\Controller;
use App\Models\Atendimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ImpressaoController extends Controller
{
    public function gerarPdf(Request $request)
    {
        // Carrega os dados do atendimento com atendimentos completos
        $atendimento = Atendimento::with('paciente', 'solicitacoes')->find($request->id);

        // Gera o PDF a partir de uma view dedicada
        $pdf = Pdf::loadView('admin.atendimentos.impressao', [
            'atendimento' => $atendimento,
        ])->setPaper('A4', 'portrait');

        // Retorna para download com nome personalizado
        return $pdf->stream('atendimento_' . $atendimento->atendimento_id . '.pdf');
    }
}
