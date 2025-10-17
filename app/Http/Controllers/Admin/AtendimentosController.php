<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atendimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AtendimentosController extends Controller
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

    public function enviarParaContato($id)
    {
        $atendimento = Atendimento::with('paciente', 'solicitacoes.procedimento.tipo_procedimento')->findOrFail($id);

        $paciente = $atendimento->paciente; // supondo relacionamento atendimento->paciente

        $mensagem = "*Sistema Atendimentos SMS*\n";
        $mensagem .= "Secretaria Municipal de Saúde\n";
        $mensagem .= "Barra de Santo Antônio/AL\n\n";

        $mensagem .= "Olá, *{$paciente->paciente_nome}*!\n\n";

        $mensagem .= "Segue as informações do seu atendimento:\n";
        $mensagem .= "Número: *{$atendimento->atendimento_numero}*\n";
        $mensagem .= "Data: *{$atendimento->atendimento_data->format('d/m/Y')}*\n\n";

        if ($atendimento->solicitacoes->isNotEmpty()) {
            $mensagem .= "*Solicitações relacionadas:*\n";

            foreach ($atendimento->solicitacoes as $solicitacao) {
                $mensagem .= "- {$solicitacao->solicitacao_numero}: {$solicitacao->procedimento->procedimento_nome} ({$solicitacao->procedimento->tipo_procedimento->tipo_procedimento_nome})\n";
            }
            $mensagem .= "\n";
        }

        $mensagem .= "Para maiores informações sobre o andamento das solicitações, favor acessar: ";
        $mensagem .= "https://atendimentos.saudebsa.com.br/consultar";

        // Aqui você pode enviar via WhatsApp usando API, por exemplo Twilio, Zenvia, etc.
        // Exemplo simples de WhatsApp Web (abre no navegador):
        $telefone    = preg_replace('/\D/', '', $paciente->paciente_contato); // só números
        $urlWhatsApp = "https://wa.me/{$telefone}?text=" . urlencode($mensagem);

        return redirect($urlWhatsApp);
    }
}
