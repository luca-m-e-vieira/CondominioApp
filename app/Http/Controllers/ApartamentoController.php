<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Condominio;
use App\Models\Morador;
use DB;
use Illuminate\Http\Request;

class ApartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Apartamento::class);
    
        if (auth()->user()->role === 'admin') {
            $apartamentos = Apartamento::paginate(30);
        } else {
            $condominioAtivo = auth()->user()->condominios()->wherePivot('ativo', true)->first();
            $apartamentos = $condominioAtivo->apartamentos()->paginate(30) ?? collect();
        }
    
        return view('apartamentos.index', compact('apartamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'sindico') {
            $condominioAtivo = $user->condominioAtivo();
            $condominios = $condominioAtivo ? collect([$condominioAtivo]) : collect();
            $moradores = $condominioAtivo 
                ? Morador::where('condominio_id', $condominioAtivo->id)->get()
                : collect();
        } else {
            $condominios = Condominio::all();
            $moradores = Morador::all();
        }
    
        return view('apartamentos.create', compact('condominios', 'moradores'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:20',
            'condominio_id' => 'required|exists:condominios,id',
            'morador_id' => 'nullable|exists:moradores,id'
        ]);
    
        Apartamento::create($validated);
    
        return redirect()->route('apartamentos.index')
                       ->with('success', 'Apartamento criado com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartamento $apartamento)
    {
        $condominios = Condominio::all();
        $moradores = Morador::where('condominio_id', $apartamento->condominio_id)->get();
        
        return view('apartamentos.edit', compact('apartamento', 'condominios', 'moradores'));
    }
    
    public function update(Request $request, Apartamento $apartamento)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:20',
            'condominio_id' => 'required|exists:condominios,id',
            'morador_id' => 'nullable|exists:moradores,id'
        ]);
    
        
        if ($request->morador_id) {
            $morador = Morador::find($request->morador_id);
            if ($morador->condominio_id != $request->condominio_id) {
                return back()->withErrors(['morador_id' => 'O morador selecionado não pertence ao condomínio escolhido']);
            }
        }
    
        $apartamento->update($validated);
    
        return redirect()->route('apartamentos.index')
                       ->with('success', 'Apartamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartamento $apartamento)
    {
        try {
            
            if ($apartamento->morador_id) {
                $apartamento->update(['morador_id' => null]);
            }
           
            $apartamento->delete();
            
            return redirect()->route('apartamentos.index')
                           ->with('success', 'Apartamento excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erro ao excluir apartamento: ' . $e->getMessage());
        }
    }

    public function desvincularMorador(Apartamento $apartamento)
    {
        
        $apartamento->update(['morador_id' => null]);
        
        return back()->with('success', 'Apartamento desvinculado!');
    }
}
