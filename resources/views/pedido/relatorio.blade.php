@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    h1 {
        text-align: center;
        margin-top: 20px;
    }

    p {
        text-align: center;
    }

    #botao-canto-superior-direito {
        position: fixed;
        top: 10px; /* Distância do topo */
        right: 10px; /* Distância da direita */
        padding: 10px;
        background-color: #e25a5a; /* Cor de fundo */
        color: #fff; /* Cor do texto */
        border: none;
        cursor: pointer;
        border-radius: 10%;
    }
</style>
@section('content')
<a href="/filtro" id="botao-canto-superior-direito" class="btn btn-secondary">Voltar</a>
    <h1>Relatório de Vendas</h1>
    <p>Período: {{ $dataInicial }} a {{ $dataFinal }}</p>
    <p>Status: {{ $status ? $statusOptions[$status] : 'Todos' }} - Pago: {{ $pago ? $pagoOptions[$pago] : 'Todos' }}</p>
    <div class="table-responsive">
        <table style="text-align: center" class="table table-striped table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Pedido</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Status</th>
                    <th scope="col">Pago</th>
                    <th scope="col">Data/Hora</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->nome }}</td>
                        <td>{{ $pedido->telefone }}</td>
                        <td>{{ $pedido->status->status }}</td>
                        <td>{{ $pedido->pago == 'S' ? 'Sim' : 'Não' }}</td>
                        <td>{{ $pedido->created_at }}</td>
                        <td>R${{ number_format($pedido->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong style="color: #e25a5a">Total de Pedidos: {{ $pedidos->count('id') }}</strong></td>
                    <td colspan="5"></td>
                    <td><strong style="color: #e25a5a">Total Geral: R${{ number_format($pedidos->sum('total'),2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>


@endsection
