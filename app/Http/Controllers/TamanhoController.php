<?php

namespace App\Http\Controllers;

use App\Models\Tamanho;
use Illuminate\Http\Request;

class TamanhoController extends Controller
{
    public function index()
    {
        $tamanhos = Tamanho::all();
        return view('produto.tamanho', compact('tamanhos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $tamanhos = strtoupper($request->input('nome'));

        $existingTamanho = Tamanho::where('nome', $tamanhos)->first();

        if ($existingTamanho) {
            return redirect()->route('tamanho.index')->with('error', 'Nome do Tamanho do Produto já existe');
        }

        $tamanhos = Tamanho::create([
            'nome' => $tamanhos,
        ]);

        return redirect()->route('tamanho.index')->with('success', 'Tamanho do Produto criado com sucesso');
    }

    public function update(Request $request, $id)
    {
        $tamanhos = Tamanho::find($id);

        if (!$tamanhos) {
            return redirect()->back()->with('error', 'Tamanho de Produto não encontrado');
        }

        $tamanhos->nome = strtoupper($request->input('nome'));

        $tamanhos->save();

        return redirect()->back()->with('success', 'Tamanho de Produto atualizado com sucesso');
    }

    public function destroy(string $id)
    {
        $tamanhos = Tamanho::findOrFail($id);

        $tamanhos->delete();

        return redirect()->route('tamanho.index')->with('error', 'Tamanho de Produto excluído com sucesso');
    }
}
