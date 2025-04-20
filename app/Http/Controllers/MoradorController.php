<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Morador;
use App\Models\Condominio;
use DB;
use Illuminate\Http\Request;

class MoradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'sindico') {
            $condominioAtivo = $user->condominioAtivo();
            $moradores = $condominioAtivo 
                ? Morador::where('condominio_id', $condominioAtivo->id)
                          ->where('expulso', false)
                          ->with(['condominio', 'apartamentos'])
                          ->paginate(10)
                : collect();
        } else {
            $moradores = Morador::with(['condominio', 'apartamentos'])->paginate(10);
        }
    
        return view('moradores.index', compact('moradores'));
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
        } else {
            $condominios = Condominio::all();
        }
    
        return view('moradores.create', compact('condominios'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'condominio_id' => 'nullable|exists:condominios,id',
            'apartamento_id' => 'nullable|exists:apartamentos,id'
        ]);
    
        $morador = Morador::create($validated);
    
        // Vincula o apartamento se foi selecionado
        if ($request->apartamento_id) {
            $apartamento = Apartamento::find($request->apartamento_id);
            $apartamento->update(['morador_id' => $morador->id]);
        }
    
        return redirect()->route('moradores.index')
                         ->with('success', 'Morador criado com sucesso!');
    }
    
    public function edit(Morador $morador)
    {
        $this->authorize('update', $morador);
        
        $condominios = auth()->user()->role === 'admin' 
            ? Condominio::all() 
            : collect();
        
        return view('moradores.edit', compact('morador', 'condominios'));
    }
    
    public function update(Request $request, Morador $morador)
    {
        $this->authorize('update', $morador);
    
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'condominio_id' => auth()->user()->role === 'admin' 
                ? 'required|exists:condominios,id' 
                : 'nullable',
            'apartamento_id' => [
                'nullable',
                'exists:apartamentos,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->condominio_id) {
                        $apartamento = Apartamento::find($value);
                        if ($apartamento->condominio_id != $request->condominio_id) {
                            $fail('O apartamento selecionado não pertence ao condomínio escolhido');
                        }
                    }
                }
            ]
        ]);
    
        DB::transaction(function () use ($morador, $validated) {
            $condominioOriginal = $morador->condominio_id;
            
            // Atualiza dados básicos
            $morador->update([
                'nome' => $validated['nome'],
                'email' => $validated['email'],
                'condominio_id' => auth()->user()->role === 'admin' 
                    ? $validated['condominio_id'] 
                    : $morador->condominio_id,
                'expulso' => false
            ]);
    
            // Se o condomínio foi alterado
            if ($condominioOriginal != $morador->condominio_id) {
                // Desvincula de todos os apartamentos do condomínio antigo
                $morador->apartamentos()
                        ->where('condominio_id', $condominioOriginal)
                        ->update(['morador_id' => null]);
            }
    
            // Vincula novo apartamento se foi selecionado
            if ($validated['apartamento_id'] ?? false) {
                $apartamento = Apartamento::find($validated['apartamento_id']);
                $apartamento->update(['morador_id' => $morador->id]);
            }
        });
    
        return redirect()->route('moradores.index')->with('success', 'Morador atualizado com sucesso!');
    }
    public function destroy(Morador $morador)
    {
        $this->authorize('delete', $morador);
        // Exclui o morador (ação independente)
        $morador->delete();
        
        return redirect()->route('moradores.index')->with('success', 'Morador excluído!');
    }
    public function expulsar(Morador $morador)
{
    $this->authorize('update', $morador);
    
    DB::transaction(function () use ($morador) {
        $morador->update([
            'condominio_id' => null,
            'expulso' => true
        ]);
        
        $morador->apartamentos()->update(['morador_id' => null]);
    });

    return redirect()->route('moradores.index')->with('success', 'Morador expulso com sucesso!');
}
}
