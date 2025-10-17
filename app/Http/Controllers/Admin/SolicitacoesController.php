<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitacao;

class SolicitacoesController extends Controller
{
    public function enviarParaPaciente($id)
    {
        $solicitacao = Solicitacao::with('atendimento.paciente')->findOrFail($id);

        $mensagem = $solicitacao->mensagemParaPaciente();

        // Exemplo WhatsApp Web
        $telefone    = preg_replace('/\D/', '', $solicitacao->atendimento->paciente->paciente_contato);
        $urlWhatsApp = "https://wa.me/{$telefone}?text=" . urlencode($mensagem);

        return redirect($urlWhatsApp);
    }
}
