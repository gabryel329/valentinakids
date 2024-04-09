<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Tamanho;
use App\Models\tipoProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::all();
        $tipoProd = tipoProd::all();
        $tamanhos = Tamanho::all();
        return view('produto.lista', compact(['produtos', 'tipoProd', 'tamanhos']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $nome = $request->input('nome');
        $preco = $request->input('preco');
        $tipo = strtoupper($request->input('tipo'));
        $descricao = $request->input('descricao');
        $imagem = $request->file('imagem');

        $existingProduto = Produto::where('nome', $nome)->first();

        if ($existingProduto) {
            return redirect()->route('produtos.index')->with('error', 'Nome de Produto já existe');
        }

        if ($imagem && $imagem->isValid()) {
            // Get filename with the extension
            $filenameWithExt = $imagem->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $imagem->getClientOriginalExtension();
            // Filename to store
            $imageName = $filename.'.'.$extension;

            // Upload Image to the 'public/images/' directory
            $imagem->move(public_path('images/'), $imageName);

            // Adicione o caminho da imagem aos dados que você está inserindo no banco de dados
            $produto = Produto::create([
                'nome' => $nome,
                'tipo' => $tipo,
                'mostrar' => 'S',
                'preco' => $preco,
                'descricao' => $descricao,
                'imagem' => $imageName, // Salva o nome da imagem com a extensão no banco de dados
            ]);
        } else {
            // Se nenhuma imagem foi enviada, crie o produto sem o campo de imagem
            $produto = Produto::create([
                'nome' => $nome,
                'tipo' => $tipo,
                'mostrar' => 'S',
                'preco' => $preco,
                'descricao' => $descricao,
            ]);
        }
         // Verifica se o campo tamanhos foi preenchido
        if ($request->has('tamanhos')) {
            // Prepara os dados para inserção na tabela pivot
            $dadosTamanhos = [];
            foreach ($request->tamanhos as $id => $tamanho) {
                if (isset($tamanho['tamanho_id'], $tamanho['quantidade']) && $tamanho['quantidade'] > 0) {
                    // A chave é o tamanho_id, e o valor é a quantidade para a tabela pivot
                    $dadosTamanhos[$tamanho['tamanho_id']] = ['quantidade' => $tamanho['quantidade']];
                }
            }

            // Anexa os tamanhos ao produto com as quantidades especificadas
            // syncWithoutDetaching mantém os tamanhos existentes e atualiza ou adiciona novos
            $produto->tamanhos()->syncWithoutDetaching($dadosTamanhos);
        }

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // Valide os dados do formulário conforme necessário

        // Encontre o produto no banco de dados
        $produto = Produto::find($id);

        // Verifique se o produto existe
        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado');
        }

        // Atualize os campos do produto
        $produto->nome = $request->input('nome');
        $produto->tipo = strtoupper($request->input('tipo'));
        $produto->preco = $request->input('preco');
        $produto->mostrar = strtoupper($request->input('mostrar'));
        $produto->descricao = $request->input('descricao');

        // Verifique se uma nova imagem foi enviada
        $novaImagem = $request->file('imagem');
        if ($novaImagem && $novaImagem->isValid()) {
            // Lógica para nomear e mover a imagem
            $extension = $novaImagem->extension();
            $imageName = md5($novaImagem->getClientOriginalName()) . "." . $extension; // Corrigir o erro de sintaxe

            $novaImagem->move(public_path('images/'), $imageName);

            // Exclua a imagem antiga se existir
            if ($produto->imagem) {
                // Certifique-se de incluir a declaração 'use Illuminate\Support\Facades\Storage;' no topo do arquivo
                Storage::delete($produto->imagem);
            }

            // Atualize o caminho da imagem no banco de dados
            $produto->imagem = $imageName;
        }

        // Salve as alterações
        $produto->save();

        // Atualize as quantidades dos tamanhos
        foreach ($request->tamanhos as $sizeId => $data) {
            $produto->tamanhos()->updateExistingPivot($sizeId, ['quantidade' => $data['quantidade']]);
        }

        // Redirecione de volta à página de origem com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Produto atualizado com sucesso');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto = Produto::findOrFail($id);

        $produto->delete();

        return redirect()->route('produtos.index')->with('error', 'Lançamento excluído com sucesso');
    }
}
