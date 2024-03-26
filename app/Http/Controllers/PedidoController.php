<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Status;
use App\Models\tipoProd;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class PedidoController extends Controller
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
        return view('pedido.create', compact(['produtos', 'tipos', 'empresa']));
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


        return redirect()->route('pedido.checkout', ['pedido' => $pedido->id]);
    }


    public function showCheckoutForm($pedido)
    {
        $empresa = \App\Models\empresa::first();
        $pedido = Pedido::findOrFail($pedido);
        $formasPagamento = FormaPagamento::all();
        $produtos = $pedido->produtos;
        $total = $pedido->total;

        return view('pedido.checkout', compact('pedido', 'formasPagamento', 'produtos', 'total', 'empresa'));
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
    /**
     * Show the form for editing the specified resource.
     */
    public function lista()
    {
        $empresa = \App\Models\empresa::first();
        $pedidos = Pedido::with(['produtos', 'formaPagamento', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();
        $statuses = Status::all();

        return view('pedido.lista', compact('pedidos', 'statuses', 'empresa'));
    }

    public function pedidos(Request $request)
    {
        $filtroStatus = $request->query('status_id');

        $query = Pedido::with(['produtos', 'formaPagamento', 'status'])->orderBy('id', 'desc')->whereDate('created_at', Carbon::today())->whereNotNull('nome');

        if ($filtroStatus !== null) {
            $query->where('status_id', $filtroStatus);
        }

        $pedidos = $query->get();

        return response()->json(['pedidos' => $pedidos]);
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->status_id = $request->input('status_id');
        $pedido->pago = $request->input('pago');
        $pedido->save();

        return redirect()->back()->with('success', 'Status atualizado com sucesso.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function filtro(Pedido $pedido)
    {
        return view('pedido.filtro');
    }

    public function relatorio(Request $request)
    {
        $dataInicialInput = $request->input('data_inicial');
        $dataFinalInput = $request->input('data_final');

        $dataInicial = (new DateTime($dataInicialInput))->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $dataFinal = (new DateTime($dataFinalInput))->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $status = $request->input('status');
        $pago = $request->input('pago');

        $statusOptions = [
            1 => 'Em Andamento',
            2 => 'Feito',
            3 => 'Deletado',
        ];

        $pagoOptions = [
            'S' => 'Sim',
            'N' => 'Não',
        ];

        $query = Pedido::whereBetween('created_at', [$dataInicial, $dataFinal])->whereNotNull('nome')->orderBy('id', 'asc');

        // Adicione a condição para o filtro de status, se fornecido
        if ($status) {
            $query->where('status_id', $status);
        }

        if($pago) {
            $query->where('pago', $pago);
        }

        $pedidos = $query->get();
        return view('pedido.relatorio', compact('pedidos', 'dataInicial', 'dataFinal', 'status', 'statusOptions', 'pago', 'pagoOptions'));
    }

}
