<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdCertificado;
use App\Services\EbdService;

class EbdCertificadoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
        // $this->middleware('permission:ebd.access');
    }

    public function index(Request $request)
    {
        $data = $this->ebdService->getCertificados($request);
        return view('admin.ebd.certificados.index', $data);
    }

    public function create()
    {
        $data = $this->ebdService->getCertificadoFormData();
        return view('admin.ebd.certificados.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'avaliacao_id' => 'nullable|exists:ebd_avaliacoes,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao',
            'descricao' => 'nullable|string|max:1000',
            'conteudo' => 'required|string',
            'carga_horaria' => 'nullable|integer|min:1',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date|after:data_emissao',
            'assinatura_coordenador' => 'nullable|string|max:255',
            'assinatura_pastor' => 'nullable|string|max:255',
            'ativo' => 'boolean'
        ]);

        $this->ebdService->createCertificado($validatedData);

        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado criado com sucesso!');
    }

    public function show(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);
        return view('admin.ebd.certificados.show', compact('certificado'));
    }

    public function edit(EbdCertificado $certificado)
    {
        $data = $this->ebdService->getCertificadoFormData();
        return view('admin.ebd.certificados.edit', array_merge($data, compact('certificado')));
    }

    public function update(Request $request, EbdCertificado $certificado)
    {
        $validatedData = $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'avaliacao_id' => 'nullable|exists:ebd_avaliacoes,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao',
            'descricao' => 'nullable|string|max:1000',
            'conteudo' => 'required|string',
            'carga_horaria' => 'nullable|integer|min:1',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date|after:data_emissao',
            'assinatura_coordenador' => 'nullable|string|max:255',
            'assinatura_pastor' => 'nullable|string|max:255',
            'ativo' => 'boolean'
        ]);

        $this->ebdService->updateCertificado($certificado, $validatedData);

        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado atualizado com sucesso!');
    }

    public function destroy(EbdCertificado $certificado)
    {
        $this->ebdService->deleteCertificado($certificado);
        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado excluído com sucesso!');
    }

    public function gerarAutomatico(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao'
        ]);

        try {
            $certificado = $this->ebdService->gerarCertificadoAutomatico($request);
            return redirect()->route('admin.ebd.certificados.show', $certificado)
                ->with('success', 'Certificado gerado automaticamente com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar certificado: ' . $e->getMessage());
        }
    }

    public function download(EbdCertificado $certificado)
    {
        try {
            return $this->ebdService->downloadCertificado($certificado);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    public function visualizar(EbdCertificado $certificado)
    {
        try {
            return $this->ebdService->visualizarCertificado($certificado);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
}