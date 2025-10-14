<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin\Pacientes;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ImpressaoController extends Controller
{
    public function gerarPdf(Request $request)
    {
        // Carrega os dados do paciente com atendimentos completos
        $paciente = Paciente::with('atendimentos')->find($request->id);

        // Gera o PDF a partir de uma view dedicada
        $pdf = Pdf::loadView('admin.pacientes.impressao', [
            'paciente' => $paciente,
        ])->setPaper('A4', 'portrait');

        // Retorna para download com nome personalizado
        return $pdf->stream('paciente_' . $paciente->paciente_id . '.pdf');
    }
}
