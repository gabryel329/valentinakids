@extends('layouts.app')
@section('content')

<section class="book_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            @php
                $empresa = \App\Models\empresa::first();
            @endphp

            @if($empresa)
                <h2>
                    <img style="width: 40%; aspect-ratio: 3/2; object-fit: contain; margin-top: 10px" src="{{ asset('images/' . $empresa->imagem) }}">
                </h2>
            @endif
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="heading_container">
                    <h2>Detalhes do Pedido</h2>
                </div>
                <div class="form_container rounded p-4" style="background-color: white;">
                    <table class="order-table">
                        <tbody>
                            @foreach ($pedido->produtos as $produto)
                                @if ($produto->pivot->quantidade > 0)
                                    <tr>
                                        <td>
                                            <div class="img-box">
                                                <img style="border-radius: 10px;" src="{{ asset('images/' . $produto->imagem) }}" alt="{{ $produto->nome }}" width="70" height="70">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="thin"><strong>{{ $produto->nome }}</strong></span>
                                                <br>
                                                <span class="small">Preço: R${{ $produto->preco }}</span>
                                                <br>
                                                <span id="qtd">Porções: {{ $produto->pivot->quantidade }}</span>
                                                <br>
                                                <span class="thin small">Subtotal: R${{ number_format($produto->preco * $produto->pivot->quantidade, 2) }}</span>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <form action="{{ route('pedido.removeProduto', ['pedido' => $pedido->id, 'produto' => $produto->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $produto->id }}">Excluir</button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="line"></div>
                    <div class="total">
                        <div class="text-center">
                            <div class="thin dense" style="color: red"><strong>TOTAL</strong></div>
                            <div id="total" name="total" class="thin dense">R${{ number_format($total, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="heading_container">
                    <h2>Dados do Cliente</h2>
                </div>
                <div class="form_container rounded p-4" style="background-color: white;">
                    <form action="{{ route('pedidos.processCheckout', ['pedido' => $pedido->id]) }}" method="POST">
                        @csrf
                        <div>
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required />
                        </div>
                        <div>
                            <label>Telefone</label>
                            <input type="text" name="telefone" class="form-control" placeholder="(XX)XXXXX-XXXX" />
                        </div>
                        <div>
                            {{-- <label>CPF</label>
                            <input type="text" name="cpf" class="form-control" placeholder="Opcional" /> --}}
                            <input style="display: none" name="total" class="form-control" value="{{ $pedido->total }}" />
                            <input style="display: none" name="id" id="id" class="form-control" value="{{ $pedido->id }}" />
                        </div>
                        <div>
                            <label>Forma Pagamento</label>
                            <select name="forma_pagamento_id" class="form-control nice-select wide">
                                <option value="" disabled selected>Escolha</option>
                                @foreach ($formasPagamento as $formaPagamento)
                                    <option value="{{ $formaPagamento->id }}" required>{{ $formaPagamento->forma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <textarea style="height: 6em;" name="obs" id="obs" class="form-control" placeholder="Informe onde você se encontra na praça"></textarea>
                        </div>
                        <div class="btn_box mt-2">
                            <button id="finalizarButton" type="button" class="btn btn-danger">Finalizar</button>
                        </div>
                    </form>
                </div>
                <div class="btn_box mt-2">
                    <a href="javascript:history.back()" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<script>
    $(document).ready(function() {
        var form = $('form');

        $('#finalizarButton').on('click', function() {
            var nome = $('input[name="nome"]').val();
            var telefone = $('input[name="telefone"]').val();
            var obs = $('textarea[name="obs"]').val();
            var formaPagamento = $('select[name="forma_pagamento_id"] option:selected').text();

            // Condição para verificar se a função do WhatsApp deve ser desabilitada
            @if($empresa && $empresa->whats == 'N')
                // Se WhatsApp desabilitado, envia o formulário diretamente
                form.submit();
            @else
                if (nome && telefone) {
                    // var message = "*Camarão da Praça*\n\n";
                    // message += "Olá, segue pedido *#" + $('#id').val() + "*\n\n";

                    // // Construa a parte da mensagem com detalhes dos produtos aqui
                    // var produtosInfo = "*Produtos*:\n";
                    // $('.order-table tbody tr').each(function() {
                    //     var nomeProduto = $(this).find('strong').text();
                    //     var quantidade = $(this).find('span[id="qtd"]').text().split(": ")[1];
                    //     var subtotal = $(this).find('.thin.small').text();

                    //     produtosInfo += nomeProduto + "\nQuantidade: " + quantidade + "\n" + subtotal + "\n\n";
                    // });

                    // message += produtosInfo;
                    // message += "*Total:* " + $('#total').text() + "\n";
                    // message += "*Forma de Pagamento:* " + formaPagamento + "\n\n";
                    // message += "*ENTREGA:*\n";
                    // message += obs + "\n\n";
                    // message += "*Nome:* " + nome + "\n";
                    // message += "*Telefone:* " + telefone;

                    // var recipientPhoneNumber = @if($empresa) {{ $empresa->whats_number }} @endif;
                    // var encodedMessage = encodeURIComponent(message);
                    // var whatsappURL = 'https://api.whatsapp.com/send?phone=' + recipientPhoneNumber + '&text=' + encodedMessage;

                    // // Abra o WhatsApp em uma nova aba
                    // window.open(whatsappURL, '_blank');

                    // Aguarde um segundo e depois envie o formulário
                    setTimeout(function() {
                        form.submit();
                    },0);
                } else {
                    alert('Preencha todos os campos antes de finalizar o pedido.');
                }
            @endif
        });
    });



    $(document).ready(function() {
        // Máscara para CPF
        $('input[name="cpf"]').inputmask("999.999.999-99", { showMaskOnHover: false });

        // Máscara para telefone
        $('input[name="telefone"]').inputmask("(99)99999-9999", { showMaskOnHover: false });
    });
</script>


@endsection
