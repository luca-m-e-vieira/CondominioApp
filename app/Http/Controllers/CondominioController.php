<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use DB;
use Illuminate\Http\Request;

class CondominioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Condominio::class, 'condominio');
    }

    public function index()
    {
        $condominios = Condominio::withCount([
                'apartamentos',
                'apartamentos as apartamentos_vagos_count' => function($query) {
                    $query->whereNull('morador_id');
                }
            ])
            ->with([
                'apartamentos.morador',
                'sindicos',
                'moradores', // Carrega moradores através de apartamentos
                'moradoresDiretos' // Carrega moradores diretos
            ])
            ->get();
    
        return view('condominios.index', compact('condominios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Condominio::class);
        return view('condominios.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', Condominio::class);
        
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
        ]);
        
        $condominio = Condominio::create($validated);
        
        return redirect()->route('condominios.index')
            ->with('success', 'Condomínio criado com sucesso!');
    }

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
    public function edit(Condominio $condominio)
    {
        $this->authorize('update', $condominio);
        return view('condominios.edit', compact('condominio'));
    }
    
    public function update(Request $request, Condominio $condominio)
    {
        $this->authorize('update', $condominio);
        
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
        ]);
        
        $condominio->update($validated);
        
        return redirect()->route('condominios.index')
            ->with('success', 'Condomínio atualizado com sucesso!');
    }
    
    public function destroy(Condominio $condominio)
    {
        $this->authorize('delete', $condominio);
        
        DB::transaction(function () use ($condominio) {
            // 1. Remove o vínculo dos moradores com os apartamentos
            $condominio->apartamentos()->each(function($apartamento) {
                $apartamento->morador_id = null; // Define morador_id como null (devido ao onDelete('set null'))
                $apartamento->save();
            });
            
            // 2. Deleta todos os apartamentos do condomínio
            $condominio->apartamentos()->delete();
            
            // 3. Remove os vínculos com síndicos
            $condominio->sindicos()->detach();
            
            // 4. Finalmente deleta o condomínio
            $condominio->delete();
        });
        
        return redirect()->route('condominios.index')
            ->with('success', 'Condomínio e todos os seus apartamentos foram excluídos com sucesso!');
    }
}
