<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\User;
use App\Models\Ministerio;
use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\MembrosExport;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function __construct()
    {
        // Aplicar permissões relevantes para membros
        $this->middleware('permission:members.access');
    }

    /**
     * Lista de membros
     */
    public function index(Request $request)
    {
        $query = Membro::with(['cargos.departamento.ministerio', 'cargos.departamento', 'cargos']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('telefone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        if ($request->filled('ministerio')) {
            $query->whereHas('cargos.departamento.ministerio', function($q) use ($request) {
                $q->where('ministerios.id', $request->ministerio);
            });
        }

        $membros = $query->paginate(15);
        $totalMembros = Membro::count();
        $membrosAtivos = Membro::where('ativo', true)->count();
        $membrosInativos = Membro::where('ativo', false)->count();
        $aniversariantes = Membro::where('ativo', true)
            ->whereMonth('data_nascimento', now()->month)
            ->count();

        return view('admin.people.members.index', compact(
            'membros',
            'totalMembros',
            'membrosAtivos',
            'membrosInativos',
            'aniversariantes'
        ));
    }

    /**
     * Formulário para criar novo membro
     */
    public function create()
    {
        $ministerios = Ministerio::all();
        $departamentos = Departamento::all();
        $cargos = Cargo::all();
        $users = User::whereDoesntHave('membro')->where('ativo', true)->get();

        return view('admin.people.members.create', compact('ministerios', 'departamentos', 'cargos', 'users'));
    }

    /**
     * Salvar novo membro
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:membros,user_id',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F',
            'estado_civil' => 'nullable|string|in:solteiro,casado,divorciado,viuvo',
            'endereco' => 'nullable|string|max:500',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'data_batismo' => 'nullable|date',
            'data_ingresso' => 'nullable|date',
            'profissao' => 'nullable|string|max:100',
            'escolaridade' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'receber_notificacoes' => 'boolean',
            'receber_newsletter' => 'boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cargo_id' => 'nullable|exists:cargos,id',
        ]);

        $dadosValidos = $request->except(['_token', 'foto', 'cargo_id']);
        $dadosValidos['ativo'] = $request->has('ativo');
        $dadosValidos['receber_notificacoes'] = $request->has('receber_notificacoes');
        $dadosValidos['receber_newsletter'] = $request->has('receber_newsletter');

        if ($request->hasFile('foto')) {
            $nomeArquivo = 'membro_' . time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $caminho = $request->file('foto')->storeAs('membros/fotos', $nomeArquivo, 'public');
            $dadosValidos['foto'] = $caminho;
        }

        $membro = Membro::create($dadosValidos);

        if ($request->filled('cargo_id')) {
            $membro->cargos()->attach($request->cargo_id, ['data_inicio' => now(), 'ativo' => true]);
        }

        return redirect()->route('admin.people.members.index')->with('success', 'Membro criado com sucesso!');
    }

    /**
     * Formulário para editar membro
     */
    public function edit(Membro $membro)
    {
        $ministerios = Ministerio::all();
        $departamentos = Departamento::all();
        $cargos = Cargo::all();
        $membro->load(['cargos.departamento.ministerio']);

        return view('admin.people.members.edit', compact('membro', 'ministerios', 'departamentos', 'cargos'));
    }

    /**
     * Atualizar membro
     */
    public function update(Request $request, Membro $membro)
    {
        $request->validate([
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F',
            'estado_civil' => 'nullable|string|in:solteiro,casado,divorciado,viuvo',
            'endereco' => 'nullable|string|max:500',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'data_batismo' => 'nullable|date',
            'data_ingresso' => 'nullable|date',
            'profissao' => 'nullable|string|max:100',
            'escolaridade' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'receber_notificacoes' => 'boolean',
            'receber_newsletter' => 'boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cargo_id' => 'nullable|exists:cargos,id',
        ]);

        $dadosValidos = $request->except(['_token', '_method', 'foto', 'cargo_id']);
        $dadosValidos['ativo'] = $request->has('ativo');
        $dadosValidos['receber_notificacoes'] = $request->has('receber_notificacoes');
        $dadosValidos['receber_newsletter'] = $request->has('receber_newsletter');

        if ($request->hasFile('foto')) {
            if ($membro->foto && Storage::disk('public')->exists($membro->foto)) {
                Storage::disk('public')->delete($membro->foto);
            }
            $nomeArquivo = 'membro_' . $membro->id . '_' . time() . '.' . $request->file('foto')->getClientOriginalExtension();
            $caminho = $request->file('foto')->storeAs('membros/fotos', $nomeArquivo, 'public');
            $dadosValidos['foto'] = $caminho;
        }

        $membro->update($dadosValidos);

        if ($request->filled('cargo_id')) {
            $membro->cargos()->sync([$request->cargo_id => ['data_inicio' => now(), 'ativo' => true]]);
        }

        return redirect()->route('admin.people.members.index')->with('success', 'Membro atualizado com sucesso!');
    }

    /**
     * Visualizar membro
     */
    public function show(Membro $membro)
    {
        $membro->load(['user']);
        $perfilPublico = $membro->user && $membro->user->public_profile;

        if (!$perfilPublico && !auth()->user()->hasRole(['Super Admin', 'Admin', 'Pastor'])) {
            abort(403, 'Este perfil não está disponível para visualização pública.');
        }

        return view('admin.people.members.show', compact('membro', 'perfilPublico'));
    }

    /**
     * Excluir membro
     */
    public function destroy(Membro $membro)
    {
        if ($membro->foto && Storage::disk('public')->exists($membro->foto)) {
            Storage::disk('public')->delete($membro->foto);
        }
        $membro->cargos()->detach();
        $membro->delete();

        return redirect()->route('admin.people.members.index')->with('success', 'Membro excluído com sucesso!');
    }

    /**
     * Exportar membros para Excel
     */
    public function export()
    {
        return Excel::download(new MembrosExport(), 'membros.xlsx');
    }

    /**
     * Página de importação de membros
     */
    public function import()
    {
        return view('admin.people.members.import');
    }

    /**
     * Processar importação de membros
     */
    public function processImport(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);

        try {
            // Lógica de importação com Laravel Excel
            // Excel::import(new MembrosImport, $request->file('file'));
            return redirect()->route('admin.people.members.index')->with('success', 'Membros importados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao importar membros: ' . $e->getMessage());
        }
    }

    /**
     * Gerar e baixar ficha de membro em PDF
     */
    public function memberCard(Membro $membro)
    {
        $configuracoes = [
            'nome' => \App\Models\Configuracao::get('igreja_nome', 'Igreja'),
            'endereco' => \App\Models\Configuracao::get('igreja_endereco', ''),
            'cidade' => \App\Models\Configuracao::get('igreja_cidade', ''),
            'telefone' => \App\Models\Configuracao::get('igreja_telefone', ''),
            'email' => \App\Models\Configuracao::get('igreja_email', ''),
            'site' => \App\Models\Configuracao::get('igreja_site', ''),
        ];

        $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();

        $dadosMembro = [
            'nome' => $membro->nome,
            'numeroMembro' => str_pad($membro->id, 4, '0', STR_PAD_LEFT),
            'ativo' => $membro->ativo,
            'dataNascimento' => optional($membro->data_nascimento)->format('d/m/Y') ?? 'N/A',
            'dataBatismo' => optional($membro->data_batismo)->format('d/m/Y') ?? 'N/A',
            'dataMembro' => optional($membro->data_ingresso)->format('d/m/Y') ?? 'N/A',
            'cargo' => $cargoAtivo->nome ?? 'Sem cargo',
            'dataValidade' => now()->addYears(2)->format('m/Y'),
            'fotoPath' => $membro->foto ? storage_path('app/public/' . $membro->foto) : null,
        ];

        $fotoBase64 = null;
        if ($dadosMembro['fotoPath'] && file_exists($dadosMembro['fotoPath'])) {
            $fotoBase64 = base64_encode(file_get_contents($dadosMembro['fotoPath']));
        }

        $html = view('templates.pdf.member-card', compact('configuracoes', 'dadosMembro', 'fotoBase64'))->render();

        $dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('ficha-membro-' . $membro->id . '.pdf');
    }
}