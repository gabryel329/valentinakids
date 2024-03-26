<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = empresa::all();
        return view('empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nome = $request->input('nome');
        $telefone = $request->input('telefone');
        $email = $request->input('email');
        $dias = $request->input('dias');
        $horario = $request->input('horario');
        $instagram = $request->input('instagram');
        $whats = $request->input('whats');
        $whats_number = $request->input('whats_number');
        $imagem = $request->file('imagem');

        $existingEmpresa = empresa::where('nome', $nome)->first();

        if ($existingEmpresa) {
            return redirect()->route('empresa.index')->with('error', 'Nome da Empresa já existe');
        }

        if ($imagem && $imagem->isValid()) {
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
            $empresa = empresa::create([
                'nome' => $nome,
                'telefone' => $telefone,
                'email' => $email,
                'instagram' => $instagram,
                'dias' => $dias,
                'horario' => $horario,
                'whats' => $whats,
                'whats_number' => $whats_number,
                'imagem' => $imageName, // Salva o nome da imagem com a extensão no banco de dados
            ]);
        } else {
            // Se nenhuma imagem foi enviada, crie o produto sem o campo de imagem
            $empresa = empresa::create([
                'nome' => $nome,
                'telefone' => $telefone,
                'email' => $email,
                'instagram' => $instagram,
                'dias' => $dias,
                'whats' => $whats,
                'whats_number' => $whats_number,
                'horario' => $horario,
            ]);
        }

        // Retrieve the company data, including the logo, after it has been saved
        $empresa = empresa::first();

        return redirect()->route('empresa.index')->with('success', 'Empresa criada com sucesso')->with('empresa', $empresa);
    }


    /**
     * Display the specified resource.
     */
    public function show(empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Valide os dados do formulário conforme necessário

        // Encontre o produto no banco de dados
        $empresas = empresa::find($id);

        // Verifique se o produto existe
        if (!$empresas) {
            return redirect()->back()->with('error', 'Empresa não encontrado');
        }

        // Atualize os campos do produto
        $empresas->nome = $request->input('nome');
        $empresas->telefone = $request->input('telefone');
        $empresas->instagram = $request->input('instagram');
        $empresas->email = $request->input('email');
        $empresas->dias = $request->input('dias');
        $empresas->whats = $request->input('whats');
        $empresas->whats_number = $request->input('whats_number');
        $empresas->horario = $request->input('horario');

        // Verifique se uma nova imagem foi enviada
        $novaImagem = $request->file('imagem');
        if ($novaImagem && $novaImagem->isValid()) {
            // Lógica para nomear e mover a imagem
            $extension = $novaImagem->extension();
            $imageName = md5($novaImagem->getClientOriginalName()) . "." . $extension; // Corrigir o erro de sintaxe

            $novaImagem->move(public_path('images/'), $imageName);

            // Exclua a imagem antiga se existir
            if ($empresas->imagem) {
                // Certifique-se de incluir a declaração 'use Illuminate\Support\Facades\Storage;' no topo do arquivo
                Storage::delete($empresas->imagem);
            }

            // Atualize o caminho da imagem no banco de dados
            $empresas->imagem = $imageName;
        }

        // Salve as alterações
        $empresas->save();

        // Redirecione de volta à página de origem com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Empresa atualizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empresa = empresa::findOrFail($id);

        $empresa->delete();

        return redirect()->route('empresa.index')->with('error', 'Empresa excluída com sucesso');
    }
}
