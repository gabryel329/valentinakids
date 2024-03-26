@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    input {
        border-radius: 10px;
        outline: none;
        border: none;
        padding: 15px 0;
        width: 50px;
        text-align: center;
        font-size: 1.5em;
        margin: 0 10px;
    }

    #cart {
        position: fixed;
        bottom: 20px;
        right: 10px;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        /* display: none; Remova esta linha */
        z-index: 1000;
        max-width: 50%; /* Defina a largura máxima desejada */
        max-height: 50%; /* Defina a altura máxima desejada */
        overflow-y: auto; /* Adicione esta linha para permitir rolar caso o conteúdo seja muito longo */
        font-size: 13px;
    }

    #cart {
        border: 1px solid #ddd;
        padding: 10px;
        width: 300px; /* ou ajuste conforme necessário */
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-footer {
        margin-top: 10px;
        text-align: right;
    }

    #cart-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #cart-items tr {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
    }

    #cart-items th,
    #cart-items td {
        padding: 10px;
    }

    #cart-items th {
        text-align: left;
        width: 33.33%;
    }

    .item-name {
        width: 33.33%;
    }

    .item-quantity,
    .item-subtotal {
        width: 33.33%;
        text-align: center;
    }


    .fas {
    font-size: 24px;
    margin-bottom: 10px;
    cursor: pointer;
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@section('content')
<div id="loader" style="display: none;">
    Loading...
</div>

    <section class="food_section layout_padding">
        <div class="heading_container heading_center">
            @php
                $empresa = \App\Models\empresa::first();
            @endphp

            @if($empresa)
                <h2>
                    <img style="width: 40%; aspect-ratio: 3/2; object-fit: contain; margin-bottom: 10px; margin-top: 10px" src="{{ asset('images/' . $empresa->imagem) }}">
                </h2>
            @endif
        </div>

        <ul class="filters_menu">
            <li class="active" data-filter="*">Todos</li>
            @foreach ($tipos as $tipo)
                <li data-filter="{{ $tipo->id }}">{{ $tipo->tipo }}</li>
            @endforeach
        </ul>
        <div class="filters-content">
            <form id="meuForm" action="{{ route('pedido.store') }}" method="POST">
                @csrf
                <div class="row grid">
                    @foreach($produtos as $produto)
                        <div class="col-sm-3 filter-item" data-tipo="{{ $produto->tipo }}" data-preco="{{ $produto->preco }}">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img style="border-radius: 10px" src="{{ asset('images/' . $produto->imagem) }}" alt="{{ $produto->nome }}">
                                    </div>
                                    <div class="detail-box">
                                        <h5>{{ $produto->nome }}</h5>
                                        <div class="options">
                                            <h6>R${{ $produto->preco }} - <small>{{ $produto->descricao }}</small></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="align-items: center">
                                                <button type="button" onclick="qtdporcao({{ $produto->id }},0)" class="sub btn btn-danger"><strong>-</strong></button>
                                                <input type="text" id="qtd{{ $produto->id }}" class="qtyBox" name="produtos[{{ $produto->id }}][quantidade]" readonly="" value="0">
                                                <button type="button" onclick="qtdporcao({{ $produto->id }},1)" class="add btn btn-success"><strong>+</strong></button>
                                                <input type="hidden" id="prod{{ $produto->id }}" name="produtos[{{ $produto->id }}][id]" value="{{ $produto->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var filterItems = document.querySelectorAll(".filter-item");
            var filterButtons = document.querySelectorAll(".filters_menu li");

            filterButtons.forEach(function (button) {
                button.addEventListener("click", function () {
                    var filterValue = this.getAttribute("data-filter");

                    // Adiciona ou remove a classe 'active' dos botões do menu
                    filterButtons.forEach(function (btn) {
                        btn.classList.remove("active");
                    });
                    this.classList.add("active");

                    // Mostra ou oculta os itens do grid com base no filtro selecionado
                    filterItems.forEach(function (item) {
                        var tipo = item.getAttribute("data-tipo");
                        if (filterValue === "*" || tipo === filterValue) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });
                });
            });

            let addBtns = document.querySelectorAll('.add');
            let subBtns = document.querySelectorAll('.sub');
            let qtyInputs = document.querySelectorAll('.qtyBox');
            let cart = document.getElementById('cart');
            let cartItems = document.getElementById('cart-items');
            let cartTotal = document.getElementById('cart-total');
            let botaoAvancar = document.getElementById("botaoEnviar");


            addBtns.forEach((addBtn, index) => {
                addBtn.addEventListener('click', () => {
                    qtyInputs[index].value = parseInt(qtyInputs[index].value) + 1;
                    updateCart(index);
                });
            });

            subBtns.forEach((subBtn, index) => {
                subBtn.addEventListener('click', () => {
                    if (qtyInputs[index].value > 0) {
                        qtyInputs[index].value = parseInt(qtyInputs[index].value) - 1;
                        updateCart(index);
                    }
                });
            });

            function updateCart(index) {
                let productName = document.querySelectorAll('.detail-box h5')[index].innerText;
                let productPrice = parseFloat(document.querySelectorAll('.filter-item')[index].getAttribute('data-preco'));
                let quantity = parseInt(qtyInputs[index].value);
                let subtotal = quantity * productPrice;

                if (quantity > 0) {
                    addToCart(productName, quantity, productPrice, subtotal);
                } else {
                    removeFromCart(productName);
                }

                updateCartTotal();
                habilitarBotaoAvancar();
            }

            function addToCart(name, quantity, productPrice, subtotal) {
                const truncatedName = name.length > 12 ? name.substring(0, 12) + '...' : name;

                // Verificar se o item já existe na tabela
                const existingItem = document.querySelector(`#cart-items tr[data-name="${name}"]`);

                // Criar uma nova linha (<tr>) para o item
                const row = document.createElement('tr');
                row.dataset.name = name;

                // Adicionar células (<td>) à linha
                const nameCell = document.createElement('td');
                nameCell.classList.add('item-name');
                nameCell.innerHTML = `<strong>${truncatedName}</strong>`;
                row.appendChild(nameCell);

                const quantityCell = document.createElement('td');
                quantityCell.classList.add('item-quantity');
                quantityCell.innerHTML = `<strong><span class="quantity">${quantity}</span></strong>`;
                row.appendChild(quantityCell);

                const subtotalCell = document.createElement('td');
                subtotalCell.classList.add('item-subtotal');
                subtotalCell.innerHTML = `<strong style="color: red;"><span class="subtotal">R$${subtotal.toFixed(2)}</span></strong>`;
                row.appendChild(subtotalCell);

                // Adicionar a nova linha à tabela
                if (existingItem) {
                    // Atualizar a linha existente
                    existingItem.innerHTML = row.innerHTML;
                } else {
                    // Adicionar uma nova linha ao final da tabela
                    const tbody = document.getElementById('cart-items');
                    tbody.appendChild(row);
                }

                // Calcular e exibir o total do carrinho
                updateCartTotal();

                // Exibir o carrinho
                const cart = document.getElementById('cart');
                cart.style.display = 'block';
            }

            function removeFromCart(name) {
                const itemToRemove = document.querySelector(`#cart-items tr[data-name="${name}"]`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }

                // Após remover, recalcular o total do carrinho
                updateCartTotal();
            }

            function updateCartTotal() {
                const cartItems = document.querySelectorAll('#cart-items tr');
                let total = 0;

                cartItems.forEach(item => {
                    const subtotal = parseFloat(item.querySelector('.item-subtotal .subtotal').innerText.replace('R$', ''));
                    total += subtotal;
                });

                // Atualizar o total do carrinho
                const cartTotalElement = document.getElementById('cart-total');
                cartTotalElement.innerText = total.toFixed(2);
            }


            function habilitarBotaoAvancar() {
                let qtyInputs = document.querySelectorAll('.qtyBox');
                let habilitar = false;

                qtyInputs.forEach(function (qtyInput) {
                    if (parseInt(qtyInput.value) > 0) {
                        habilitar = true;
                    }
                });

                botaoAvancar.disabled = !habilitar;
            }

            // Adicione este trecho para atualizar o carrinho ao carregar a página
            updateCartTotal();
            habilitarBotaoAvancar();

            // Adicione este trecho para submeter o formulário quando o botão "AVANÇAR" for clicado
            botaoAvancar.addEventListener("click", function () {
                document.getElementById("meuForm").submit();
            });
        });

        function showLoader() {
            document.getElementById('loader').style.display = 'block';
        }

        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }

        // Your Swal script for success message
        @if(session('success'))
            Swal.fire({
                title: "Camarão da Praça!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#e25a5a"
            });
        @endif

        // Your Swal script for error message
        @if(session('error'))
            Swal.fire({
                title: "Camarão da Praça!",
                text: "{{ session('error') }}",
                icon: "error"
            });
        @endif
    </script>

    <!-- Carrinho de Compras -->
    <div id="cart">
        <i class="fas fa-shopping-cart"></i>
        <div class="col-mt-2">
            <button id="botaoEnviar" class="btn btn-danger btn-block" id="avancar-button" disabled>AVANÇAR</button>
        </div>
        <div class="cart-header">
            <ul id="cart-items">
            </ul>
        </div>
        <div class="cart-footer">
            <strong>Total: <span style="color: red">R$</span><span id="cart-total" style="color: red">0.00</span></strong>
        </div>
    </div>


@endsection
