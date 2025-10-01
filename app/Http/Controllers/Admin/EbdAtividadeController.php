<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EbdService;
use App\Models\Atividade;
use App\Models\EbdLicao;
use Illuminate\Http\Request;

class EbdAtividadeController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
        // $this->middleware('permission:ebd.manage');
    }

    /**
     * Lista as atividades de uma lição específica.
     */
    public function index(EbdLicao $licao)
    {
        $atividades = $this->ebdService->getAtividadesPorLicao($licao);
        return view('admin.ebd.atividades.index', compact('licao', 'atividades'));
    }

    /**
     * Mostra o formulário para criar uma nova atividade para uma lição.
     */
    public function create(EbdLicao $licao)
    {
        return view('admin.ebd.atividades.create', compact('licao'));
    }

    /**
     * Armazena uma nova atividade.
     */
    public function store(Request $request, EbdLicao $licao)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:leitura_dirigida,reflexao_pessoal,pesquisa_biblica,trabalho_em_grupo,memorizacao,projeto_especial',
            'pontuacao_maxima' => 'required|integer|min:0',
            'data_entrega' => 'nullable|date',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        $data['licao_id'] = $licao->id;

        $this->ebdService->createAtividade($data);

        return redirect()->route('admin.ebd.licoes.show', $licao)->with('success', 'Atividade criada com sucesso.');
    }

    /**
     * Mostra o formulário para editar uma atividade.
     */
    public function edit(Atividade $atividade)
    {
        $licao = $atividade->licao;
        return view('admin.ebd.atividades.edit', compact('atividade', 'licao'));
    }

    /**
     * Atualiza uma atividade.
     */
    public function update(Request $request, Atividade $atividade)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:leitura_dirigida,reflexao_pessoal,pesquisa_biblica,trabalho_em_grupo,memorizacao,projeto_especial',
            'pontuacao_maxima' => 'required|integer|min:0',
            'data_entrega' => 'nullable|date',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->updateAtividade($atividade, $data);

        return redirect()->route('admin.ebd.licoes.show', $atividade->licao)->with('success', 'Atividade atualizada com sucesso.');
    }

    /**
     * Remove uma atividade.
     */
    public function destroy(Atividade $atividade)
    {
        $licao = $atividade->licao;
        $this->ebdService->deleteAtividade($atividade);
        return redirect()->route('admin.ebd.licoes.show', $licao)->with('success', 'Atividade excluída com sucesso.');
    }
}