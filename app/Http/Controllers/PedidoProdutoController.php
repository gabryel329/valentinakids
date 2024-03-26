<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use App\Models\tipoProd;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PedidoProdutoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'store', 'showCheckoutForm', 'processCheckout','removeProduto','lista','update']);
    }

    public function index()
    {
        $empresa = \App\Models\empresa::first();
        $produtos = Produto::where('mostrar', 'S')->get();
        $tipos = tipoProd::all();
        return view('pedido.createSW', compact(['produtos', 'tipos', 'empresa']));
    }

    public function store(Request $request)
    {
        $data = [];

        $total = 0;

        foreach ($request->produtos as $produtoId => $produtoData) {
            $prod = Produto::find($produtoId);
            $total += $prod->preco * $produtoData['quantidade'];
        }

        $data['total'] = $total;

        $pedido = Pedido::create($data);

        foreach ($request->produtos as $produtoId => $produtoData) {
            $quantidade = $produtoData['quantidade'];

            if ($quantidade > 0) { // Verifique se a quantidade Ã© maior que 0 antes de adicionar ao pedido
                $pedido->produtos()->attach($produtoId, ['quantidade' => $quantidade]);
            }
        }


        return redirect()->route('pedidos.checkout', ['pedido' => $pedido->id]);
    }


    public function showCheckoutForm($pedido)
    {
        $empresa = \App\Models\empresa::first();
        $pedido = Pedido::findOrFail($pedido);
        $formasPagamento = FormaPagamento::all();
        $produtos = $pedido->produtos;
        $total = $pedido->total;

        return view('pedido.checkoutSW', compact('pedido', 'formasPagamento', 'produtos', 'total', 'empresa'));
    }

    public function processCheckout(Request $request, $pedido)
    {
        try {
            echo '<script>showLoader();</script>';

            $pedido = Pedido::findOrFail($pedido);
            $novaDataHora = Carbon::now()->subHours(3);
            $pedido->update([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'telefone' => $request->telefone,
                'obs' => $request->obs,
                'status_id' => '1',
                'pago' => 'N',
                'forma_pagamento_id' => $request->forma_pagamento_id,
                'total' => $request->total,
                'created_at' => $novaDataHora,
            ]);

            echo '<script>hideLoader();</script>';

            return redirect()->route('pedidos.index')->with('success', 'Pedido #' . $pedido->id . ' concluí­do com sucesso!');
        } catch (\Exception $e) {
            // Handle exceptions
            echo '<script>hideLoader();</script>';
            return redirect()->back()->with('error', 'Erro ao processar o pedido.');
        }
    }

    public function removeProduto(Request $request, Pedido $pedido, $produto)
    {
        // Encontre o produto no pedido pelo ID
        $produtoRemover = $pedido->produtos()->findOrFail($produto);

        // Remova o produto do pedido
        $pedido->produtos()->detach($produtoRemover->id);

        return redirect()->back()->with('success', 'Produto removido com sucesso do pedido.');
    }
}
