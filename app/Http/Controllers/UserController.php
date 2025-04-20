<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user'); // Remove o middleware 'can:admin'
    }
    
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        
        $sindicos = User::where('role', 'sindico')
                    ->with(['condominios' => function($query) {
                        $query->withPivot('ativo');
                    }])
                    ->get();
        
        // Obter TODOS os condomínios para a lista de seleção
        $condominios = Condominio::all();
        
        // Condomínios disponíveis para vincular (opcional, se ainda quiser usar)
        $condominiosDisponiveis = Condominio::whereDoesntHave('sindicos', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
    
        return view('users.index', compact('admins', 'sindicos', 'condominios', 'condominiosDisponiveis'));
    }

    /**
     * Show the form for creating a new resource.
     */
// Método create
public function create()
{
    $condominios = Condominio::all();
    return view('users.create', compact('condominios'));
}

// Método store
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|in:admin,sindico'
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role']
    ]);

    return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
}

// Método edit
public function edit(User $user)
{
    return view('users.edit', compact('user'));
}

// Método update
public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:8',
        'role' => 'required|in:admin,sindico'
    ]);

    // Se mudar de admin para síndico, remove vínculos existentes
    if ($user->role === 'admin' && $validated['role'] === 'sindico') {
        $user->condominios()->detach();
    }
    // Se mudar de síndico para admin, remove todos os vínculos
    elseif ($user->role === 'sindico' && $validated['role'] === 'admin') {
        $user->condominios()->detach();
    }

    // Atualiza a senha apenas se foi fornecida
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('users.index')
                   ->with('success', 'Usuário atualizado com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Verifica se o usuário não está tentando se auto-deletar
        if ($user->id === auth()->id()) {
            return redirect()->back()
                           ->with('error', 'Você não pode excluir sua própria conta!');
        }
    
        // Remove todos os vínculos com condomínios primeiro (se for síndico)
        if ($user->role === 'sindico') {
            $user->condominios()->detach();
        }
    
        // Deleta o usuário
        $user->delete();
    
        return redirect()->route('users.index')
                       ->with('success', 'Usuário excluído com sucesso!');
    }

    public function vincularCondominio(Request $request, User $user)
    {
        $request->validate([
            'condominio_id' => 'required|exists:condominios,id'
        ]);
    
        // Verifica se já está vinculado
        if ($user->condominios()->where('condominio_id', $request->condominio_id)->exists()) {
            return back()->with('error', 'Este síndico já está vinculado a este condomínio');
        }
    
        // Vincula como inativo por padrão
        $user->condominios()->attach($request->condominio_id, [
            'ativo' => false
        ]);
    
        return back()->with('success', 'Síndico vinculado com sucesso!');
    }
    
    public function desvincularCondominio(Request $request, User $user)
    {
        $request->validate([
            'condominio_id' => 'required|exists:condominios,id'
        ]);
    
        $user->condominios()->detach($request->condominio_id);
    
        return back()->with('success', 'Síndico desvinculado com sucesso!');
    }
    
    public function toggleActive(Request $request, User $user)
    {
        $request->validate([
            'condominio_id' => 'required|exists:condominios,id'
        ]);
    
        $condominioId = $request->condominio_id;
        $currentStatus = $user->condominios()
                            ->where('condominio_id', $condominioId)
                            ->first()
                            ->pivot
                            ->ativo;
    
        DB::transaction(function () use ($user, $condominioId, $currentStatus) {
            if ($currentStatus) {
                // Se está ativo, apenas desativa
                $user->condominios()->updateExistingPivot($condominioId, [
                    'ativo' => false
                ]);
            } else {
                // Se está inativo:
                // 1. Desativa todos os outros síndicos deste condomínio
                DB::table('sindico_condominio')
                    ->where('condominio_id', $condominioId)
                    ->update(['ativo' => false]);
    
                // 2. Desativa todos os outros condomínios deste síndico
                $user->condominios()
                    ->where('condominio_id', '!=', $condominioId)
                    ->update(['ativo' => false]);
    
                // 3. Ativa este vínculo específico
                $user->condominios()->updateExistingPivot($condominioId, [
                    'ativo' => true
                ]);
            }
        });
    
        return back()->with('success', 'Status atualizado com sucesso!');
    }
}
