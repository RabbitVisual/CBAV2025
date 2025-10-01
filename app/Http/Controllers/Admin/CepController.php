<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cep::query();
        
        // Filtros de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('localidade', 'like', "%{$search}%")
                  ->orWhere('uf', 'like', "%{$search}%")
                  ->orWhere('cep_inicial', 'like', "%{$search}%")
                  ->orWhere('cep_final', 'like', "%{$search}%")
                  ->orWhere('faixa_de_cep', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('uf')) {
            $query->where('uf', $request->uf);
        }
        
        $ceps = $query->orderBy('uf')->orderBy('localidade')->paginate(20);
        
        // Lista de UFs para o filtro
        $ufs = Cep::select('uf')->distinct()->orderBy('uf')->pluck('uf');
        
        return view('admin.ceps.index', compact('ceps', 'ufs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ceps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'localidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'cep_inicial' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'cep_final' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'faixa_de_cep' => 'nullable|string|max:255',
            'cod_ibge' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ], [
            'localidade.required' => 'O campo localidade é obrigatório.',
            'uf.required' => 'O campo UF é obrigatório.',
            'uf.size' => 'O campo UF deve ter exatamente 2 caracteres.',
            'cep_inicial.required' => 'O campo CEP inicial é obrigatório.',
            'cep_inicial.size' => 'O CEP inicial deve ter exatamente 8 dígitos.',
            'cep_inicial.regex' => 'O CEP inicial deve conter apenas números.',
            'cep_final.required' => 'O campo CEP final é obrigatório.',
            'cep_final.size' => 'O CEP final deve ter exatamente 8 dígitos.',
            'cep_final.regex' => 'O CEP final deve conter apenas números.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verificar se não há sobreposição de faixas de CEP
        $overlapping = Cep::where(function($query) use ($request) {
            $query->whereBetween('cep_inicial', [$request->cep_inicial, $request->cep_final])
                  ->orWhereBetween('cep_final', [$request->cep_inicial, $request->cep_final])
                  ->orWhere(function($q) use ($request) {
                      $q->where('cep_inicial', '<=', $request->cep_inicial)
                        ->where('cep_final', '>=', $request->cep_final);
                  });
        })->exists();
        
        if ($overlapping) {
            return redirect()->back()
                ->withErrors(['cep_inicial' => 'Já existe uma faixa de CEP que sobrepõe esta faixa.'])
                ->withInput();
        }
        
        Cep::create($request->all());
        
        return redirect()->route('admin.ceps.index')
            ->with('success', 'CEP criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cep $cep)
    {
        return view('admin.ceps.show', compact('cep'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cep $cep)
    {
        return view('admin.ceps.edit', compact('cep'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cep $cep)
    {
        $validator = Validator::make($request->all(), [
            'localidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'cep_inicial' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'cep_final' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'faixa_de_cep' => 'nullable|string|max:255',
            'cod_ibge' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ], [
            'localidade.required' => 'O campo localidade é obrigatório.',
            'uf.required' => 'O campo UF é obrigatório.',
            'uf.size' => 'O campo UF deve ter exatamente 2 caracteres.',
            'cep_inicial.required' => 'O campo CEP inicial é obrigatório.',
            'cep_inicial.size' => 'O CEP inicial deve ter exatamente 8 dígitos.',
            'cep_inicial.regex' => 'O CEP inicial deve conter apenas números.',
            'cep_final.required' => 'O campo CEP final é obrigatório.',
            'cep_final.size' => 'O CEP final deve ter exatamente 8 dígitos.',
            'cep_final.regex' => 'O CEP final deve conter apenas números.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verificar se não há sobreposição de faixas de CEP (excluindo o registro atual)
        $overlapping = Cep::where('id', '!=', $cep->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('cep_inicial', [$request->cep_inicial, $request->cep_final])
                      ->orWhereBetween('cep_final', [$request->cep_inicial, $request->cep_final])
                      ->orWhere(function($q) use ($request) {
                          $q->where('cep_inicial', '<=', $request->cep_inicial)
                            ->where('cep_final', '>=', $request->cep_final);
                      });
            })->exists();
        
        if ($overlapping) {
            return redirect()->back()
                ->withErrors(['cep_inicial' => 'Já existe uma faixa de CEP que sobrepõe esta faixa.'])
                ->withInput();
        }
        
        $cep->update($request->all());
        
        return redirect()->route('admin.ceps.index')
            ->with('success', 'CEP atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cep $cep)
    {
        try {
            $cep->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'CEP excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir CEP: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Buscar CEP para preenchimento automático
     */
    public function buscar($cep)
    {
        try {
            $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
            
            if (strlen($cepLimpo) !== 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP deve conter 8 dígitos'
                ], 400);
            }
            
            $cepData = Cep::where('cep_inicial', '<=', $cepLimpo)
                ->where('cep_final', '>=', $cepLimpo)
                ->first();
            
            if ($cepData) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'cep' => $cep,
                        'logradouro' => $cepData->faixa_de_cep ?? '',
                        'localidade' => $cepData->localidade,
                        'uf' => $cepData->uf,
                        'cidade' => $cepData->localidade,
                        'estado' => $cepData->uf,
                        'ibge' => $cepData->cod_ibge ?? '',
                        'latitude' => $cepData->latitude,
                        'longitude' => $cepData->longitude
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP não encontrado na base de dados'
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar CEP: ' . $e->getMessage()
            ], 500);
        }
    }
}
