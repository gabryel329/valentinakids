<?php

namespace App\Http\Controllers;

use App\Models\tipoProd;
use Illuminate\Http\Request;

class TipoProdController extends Controller
{
    public function index()
    {
        $tipos = tipoProd::all();
        return view('produto.tipo', compact('tipos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $tipo = strtoupper($request->input('tipo'));

        $existingTipoProduto = tipoProd::where('tipo', $tipo)->first();

        if ($existingTipoProduto) {
            return redirect()->route('tipoProd.index')->with('error', 'Nome do Tipo do Produto já existe');
        }

        $tipo = tipoProd::create([
            'tipo' => $tipo,
        ]);

        return redirect()->route('tipoProd.index')->with('success', 'Tipo do Produto criado com sucesso');
    }

    public function update(Request $request, $id)
    {
        $tipo = tipoProd::find($id);

        if (!$tipo) {
            return redirect()->back()->with('error', 'Tipo de Produto não encontrado');
        }

        $tipo->tipo = strtoupper($request->input('tipo'));

        $tipo->save();

        return redirect()->back()->with('success', 'Tipo de Produto atualizado com sucesso');
    }

    public function destroy(string $id)
    {
        $tipo = tipoProd::findOrFail($id);

        $tipo->delete();

        return redirect()->route('tipoProd.index')->with('error', 'Tipo de Produto excluído com sucesso');
    }
}
