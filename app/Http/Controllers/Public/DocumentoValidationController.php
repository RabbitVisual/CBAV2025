<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DocumentoDeclaracaoAnual;
use App\Models\DocumentoBaixa;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DocumentoValidationController extends Controller
{
    /**
     * Validar documento genérico por hash
     */
    public function validarDocumento($hash)
    {
        // Tentar encontrar documento de declaração anual
        $documentoDeclaracao = DocumentoDeclaracaoAnual::where('hash_documento', $hash)->first();
        if ($documentoDeclaracao) {
            return $this->validarDeclaracaoAnual($hash);
        }

        // Tentar encontrar documento de baixa
        $documentoBaixa = DocumentoBaixa::where('hash_documento', $hash)->first();
        if ($documentoBaixa) {
            return $this->validarBaixa($hash);
        }

        return $this->documentoNaoEncontrado();
    }

    /**
     * Validar documento de declaração anual
     */
    public function validarDeclaracaoAnual($hash)
    {
        $documento = DocumentoDeclaracaoAnual::with(['igreja', 'validadoPor'])
            ->where('hash_documento', $hash)
            ->first();

        if (!$documento) {
            \Log::warning('Documento não encontrado', ['hash' => $hash]);
            return $this->documentoNaoEncontrado();
        }

        // Verificar se o documento é válido
        $valido = $documento->validar();
        $multaJuros = $documento->calcularMultaJuros();

        // Log detalhado da validação
        \Log::info('Validação pública de documento de declaração anual', [
            'hash' => $hash,
            'documento_id' => $documento->id,
            'valido' => $valido,
            'status' => $documento->status,
            'hash_documento' => $documento->hash_documento,
            'protocolo_receita' => $documento->protocolo_receita,
            'numero_documento' => $documento->numero_documento,
            'valor_total' => $documento->valor_total,
            'data_emissao' => $documento->data_emissao,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return view('public.validacao.declaracao-anual', compact('documento', 'valido', 'multaJuros'));
    }

    /**
     * Validar documento de baixa
     */
    public function validarBaixa($hash)
    {
        $documento = DocumentoBaixa::with(['transacao.membro', 'transacao.campanha'])
            ->where('hash_documento', $hash)
            ->first();

        if (!$documento) {
            return $this->documentoNaoEncontrado();
        }

        // Verificar se o documento é válido
        $valido = $documento->validarFormatoDocumento();
        $multaJuros = $documento->calcularMultaJuros();

        // Log da validação
        \Log::info('Validação pública de documento de baixa', [
            'hash' => $hash,
            'documento_id' => $documento->id,
            'valido' => $valido,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return view('public.validacao.baixa', compact('documento', 'valido', 'multaJuros'));
    }

    /**
     * Validar comprovante de transação
     */
    public function validarComprovante(Request $request)
    {
        $codigo = $request->get('codigo');
        $transacao = null;
        $valido = false;
        $mensagem = '';

        if ($codigo) {
            // Buscar transação pelo código de verificação
            $transacoes = Transacao::with(['membro.user', 'campanha'])->get();
            
            foreach ($transacoes as $t) {
                $codigoVerificacao = strtoupper(substr(md5($t->id . $t->created_at), 0, 8));
                if ($codigoVerificacao === strtoupper($codigo)) {
                    $transacao = $t;
                    $valido = true;
                    break;
                }
            }

            if (!$transacao) {
                $mensagem = 'Código de verificação inválido ou comprovante não encontrado.';
            }

            // Log da validação
            \Log::info('Validação pública de comprovante', [
                'codigo' => $codigo,
                'encontrado' => $valido,
                'transacao_id' => $transacao ? $transacao->id : null,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }

        return view('public.validacao.comprovante', compact('transacao', 'valido', 'mensagem', 'codigo'));
    }

    /**
     * Verificar documento via POST (para formulários)
     */
    public function verificarDocumento(Request $request)
    {
        $request->validate([
            'hash' => 'required|string|min:10',
            'tipo' => 'nullable|in:declaracao-anual,baixa'
        ]);

        $hash = $request->hash;
        $tipo = $request->tipo;

        // Cache para evitar spam
        $cacheKey = "validacao_{$hash}_" . request()->ip();
        if (Cache::has($cacheKey)) {
            return back()->with('error', 'Muitas tentativas. Aguarde alguns minutos.');
        }

        Cache::put($cacheKey, true, 300); // 5 minutos

        if ($tipo === 'declaracao-anual') {
            return redirect()->route('validacao.declaracao-anual', $hash);
        } elseif ($tipo === 'baixa') {
            return redirect()->route('validacao.baixa', $hash);
        } else {
            return redirect()->route('validacao.documento', $hash);
        }
    }

    /**
     * Página de documento não encontrado
     */
    private function documentoNaoEncontrado()
    {
        return view('public.validacao.nao-encontrado');
    }
} 