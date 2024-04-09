@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<style>
    .menu__item {
        padding: 20px;
        color: #333;
        font-weight: 600;
        font-size: 20px;
        transition: color 0.3s;
        margin-bottom: 10px
    }
    .menu__item:hover {
        color: red;
    }
</style>


@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

<section class="food_section layout_padding">
    <div class="container">
        <div class="dropdown" style="margin-top: 10px">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Menu
            </a>

            <ul class="dropdown-menu">
                <li>
                    <a class="menu__item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Sair') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                <li><a class="menu__item" href="/filtro">Relatorio</a></li>
                <li><a class="menu__item" href="/produtos">Produtos</a></li>
                <li><a class="menu__item" href="/tipoProd">Tipo</a></li>
                <li><a class="menu__item" href="/tamanho">Tamanho</a></li>
                <li><a class="menu__item" href="/Empresa">Empresa</a></li>
            </ul>
        </div>
        <div class="heading_container heading_center">
            @php
                $empresa = \App\Models\empresa::first();
            @endphp

            @if($empresa)
                <h2>
                    <img style="width: 40%; aspect-ratio: 3/2; object-fit: contain;" src="{{ asset('images/' . $empresa->imagem) }}">
                </h2>
            @endif
        </div>
        <div class="row filters_menu">
            <div class="col-md-12">
                <div class="btn-group filters_menu" role="group" aria-label="Filtrar por status">
                    <ul class="filters_menu" id="filtro-tipo">
                        <li data-tipo="">Todos</li>
                        <li class="active" data-tipo="1">Em Andamento</li>
                        <li data-tipo="2">Feito</li>
                        <li data-tipo="3">Deletado</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input class="form-control type="text" id="filterInput" placeholder="Filtrar pelo nome" oninput="filterCards()">
            </div>
        </div>
        <div class="filters-content" id="lista-de-pedidos">
            <!-- Aqui serão exibidos os produtos via AJAX -->
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function filterCards() {
        // Get the input value
        var filterValue = document.getElementById("filterInput").value.toUpperCase();

        // Get all card elements
        var cards = document.querySelectorAll('.card');

        // Loop through each card
        cards.forEach(function(card) {
            // Get the text content from the card
            var cardText = card.innerText.toUpperCase();

            // Check if the card text contains the filter value
            if (cardText.includes(filterValue)) {
                card.style.display = ""; // Show the card
            } else {
                card.style.display = "none"; // Hide the card
            }
        });
    }

    function carregarPedidos(filtroStatus) {
        var url = '/pedidos/lista';
        if (filtroStatus !== undefined && filtroStatus !== '') {
            url += '?status_id=' + filtroStatus;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.pedidos) {
                    exibirPedidos(response.pedidos);
                } else {
                    console.error('Resposta da lista de pedidos inválida:', response);
                }
            },
            error: function(error) {
                console.error('Erro ao obter a lista de pedidos:', error);
            }
        });
    }

    function exibirPedidos(pedidos) {
        var listaDePedidos = $('#lista-de-pedidos');
        listaDePedidos.empty();

        var pedidosPorStatus = {};

        pedidos.forEach(function(pedido) {
            if (!pedidosPorStatus[pedido.status_id]) {
                pedidosPorStatus[pedido.status_id] = [];
            }

            pedidosPorStatus[pedido.status_id].push(pedido);
        });

        for (var status_id in pedidosPorStatus) {
            if (pedidosPorStatus.hasOwnProperty(status_id)) {
                exibirStatus_id(status_id, pedidosPorStatus[status_id]);
            }
        }
    }

    var status_id = 0;

    var statusNames = {
    1: 'Em Andamento',
    2: 'Feito',
    3: 'Deletado'
    };

    function exibirStatus_id(status_id, pedidos) {
        var listaDePedidos = $('#lista-de-pedidos');
        if (statusNames.hasOwnProperty(status_id)) {
        listaDePedidos.append('<div class="status_id-separator"><br><h2>' + statusNames[status_id] + '</h2></div>');
        } else {
        console.error('Nome do status não encontrado para o ID ' + status_id);
        }
        let htmlString = '<div class="row">';
        pedidos.forEach(pedido => {

            const formasPagamento = {
                1: 'PIX',
                2: 'DEBITO',
                3: 'CREDITO',
                4: 'DINHEIRO'
            };

            const pago = {
                'S': 'Sim',
                'N': 'Não'
            };

            htmlString += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="text-align: center">
                            Pedido #${pedido.id}
                            ${pedido.nome ? `<p class="card-text"><small class="text-muted">${pedido.nome} - ${pedido.telefone}</small></p>` : ''}
                            <p class="card-text"><small class="text-muted">${formatarData(pedido.created_at)}</small></p>
                        </div>
                        <div class="card-body">
                            <ul>
                                ${pedido.produtos.map(produto => `
                                    <li>${produto.nome} - Quantidade: ${produto.pivot.quantidade}</li>
                                `).join('')}
                                ${pedido.forma_pagamento_id ? `<li>Forma Pagamento:<strong> ${formasPagamento[pedido.forma_pagamento_id]}</strong></li>` : ''}
                                <li><strong style="color: red">Total: R$${Number(pedido.total).toFixed(2)}</strong></li>

                            </ul>
                            ${pedido.obs ? `Observação:<p class="card-text"><small class="text-muted">${pedido.obs}</small></p>` : ''}
                        </div>
                        <div class="card-footer">
                            <div class="col-md-12">
                                <form action="/pedidos/${pedido.id}/update" method="POST" class="update-status-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="exampleRadios1">Pago:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pago" value="S" id="pago1" ${pedido.pago === 'S' ? 'checked' : ''}>
                                                <label class="form-check-label" for="pago1">
                                                    Sim
                                                </label>
                                                </div>
                                                <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pago" value="N" id="pago2" ${pedido.pago === 'N' ? 'checked' : ''}>
                                                <label class="form-check-label" for="pago2">
                                                    Não
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="hidden" name="pedido_id" value="${pedido.id}">
                                            <div class="form-group">
                                                <label for="status_id">Status do Pedido:</label>
                                                <select name="status_id" class="form-control">
                                                    <option value="1" ${pedido.status_id == 1 ? 'selected' : ''}>Em Andamento</option>
                                                    <option value="2" ${pedido.status_id == 2 ? 'selected' : ''}>Feito</option>
                                                    <option value="3" ${pedido.status_id == 3 ? 'selected' : ''}>Deletado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="form-control btn btn-danger" type="submit">Atualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`;
        });

        htmlString += '</div>';

        function formatarData(data) {
            const dataFormatada = new Date(data);
            const dia = String(dataFormatada.getDate()).padStart(2, '0');
            const mes = String(dataFormatada.getMonth() + 1).padStart(2, '0');
            const ano = dataFormatada.getFullYear();
            const horas = String(dataFormatada.getHours()).padStart(2, '0');
            const minutos = String(dataFormatada.getMinutes()).padStart(2, '0');
            const segundos = String(dataFormatada.getSeconds()).padStart(2, '0');

            return `${dia}-${mes}-${ano} ${horas}:${minutos}:${segundos}`;
        }

        listaDePedidos.append(htmlString);
    }

    $(document).ready(function() {
        carregarPedidos('');

        $('.filters_menu li').click(function() {
            $('.filters_menu li').removeClass('active');
            $(this).addClass('active');
            var status_idSelecionado = $(this).data('tipo');
            carregarPedidos(status_idSelecionado);
        });
    });

    // Função para atualizar a página a cada 30 segundos
    function atualizarPagina() {
        setTimeout(function() {
            location.reload(); // Recarrega a página
        }, 60000); // 30 segundos em milissegundos
    }

    // Chama a função na carga inicial da página
    atualizarPagina();

</script>

@endsection
