
@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
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

<main id="main" class="main">
    <a href="/pedidos" id="botao-canto-superior-direito" class="btn btn-secondary">Voltar</a>
    <div class="pagetitle">
      <h1>Lista de Produtos</h1>
    </div><!-- End Page Title -->
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
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div>
                            <!-- Small Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#smallModal">
                                Novo
                            </button>

                            <div class="modal fade" id="smallModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Crie um novo Produto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Adicione o formulário aqui -->
                                            <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Nome</label>
                                                                <input type="text" class="form-control" id="" name="nome">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Tipo</label>
                                                                <select class="form-control" id="tipo" name="tipo">
                                                                    <option disabled selected required>Escolha</option>
                                                                    @foreach ($tipoProd as $tipoProds)
                                                                        <option value="{{ $tipoProds->id }}" required>{{ $tipoProds->tipo }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Preço</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">R$</span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="preco" name="preco" onblur="formatarPreco()">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                            <label for="">Imagem</label>
                                                                <input class="form-control" type="file" name="imagem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Descrição</label>
                                                                <input type="text" class="form-control" id="" name="descricao">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Preço</th>
                                        <th scope="col">Estoque (S/N)</th>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Excluir</th>
                                        <th scope="col">Detalhes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($produtos as $produto)

                                        <tr>
                                            <td scope="row">{{ $produto->id }}</td>
                                            <td>{{ $produto->nome }}</td>
                                            <td>R${{ $produto->preco }}</td>
                                            <td style="text-align: center">{{ $produto->mostrar }}</td>
                                            <td>{{ $produto->descricao }}</td>
                                            <td>
                                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="post"
                                                    class="ms-2">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">Excluir</button>

                                                </form>
                                            </td>
                                            <td>
                                                <div>
                                                    <!-- Botão de edição que abre o modal -->
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $produto->id }}">
                                                        Editar
                                                    </button>
                                                </div>

                                                <!-- Modal de edição para cada investimento -->
                                                <div class="modal fade" id="editModal{{ $produto->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar produto</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Formulário de edição para este produto -->
                                                                <form method="POST" action="{{ route('produtos.update', ['id' => $produto->id]) }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Nome</label>
                                                                                    <input type="text" class="form-control" id="" name="nome" value="{{ $produto->nome }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Tipo</label>
                                                                                    <input type="text" class="form-control" id="" name="tipo" value="{{ $produto->tipo }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Preço</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text">R$</span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control" id="preco2" name="preco" onblur="formatarPreco2()" value="{{ $produto->preco }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Imagem</label>
                                                                                    <input type="file" class="form-control" id="" name="imagem" value="{{ $produto->imagem }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <label for="">Descrição</label>
                                                                                    <input type="text" class="form-control" id="" name="descricao" value="{{ $produto->descricao }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="">Estoque (S/N)</label>
                                                                                    <input type="text" class="form-control" id="" name="mostrar" value="{{ $produto->mostrar }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Fim do modal de edição -->
                                            </td>
                                        @empty
                                            <p class="alert-warning" style="font-size:22px; text-align:center;">Nenhum Tipo de Produto Cadastrado</p>

                                        </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
<script>
    function formatarPreco() {
        var precoInput = document.getElementById('preco');
        var precoValue = precoInput.value;

        // Remover espaços em branco e substituir vírgulas por pontos
        precoValue = precoValue.replace(/\s/g, '').replace(',', '.');

        // Converter para número e dividir por 100 (assumindo que o valor original é em centavos)
        precoValue = parseFloat(precoValue / 100).toFixed(2);

        // Atualizar o valor no input
        precoInput.value = precoValue;
    }

    function formatarPreco2() {
        var precoInput = document.getElementById('preco2');
        var precoValue = precoInput.value;

        // Remover espaços em branco e substituir vírgulas por pontos
        precoValue = precoValue.replace(/\s/g, '').replace(',', '.');

        // Converter para número e dividir por 100 (assumindo que o valor original é em centavos)
        precoValue = parseFloat(precoValue / 100).toFixed(2);

        // Atualizar o valor no input
        precoInput.value = precoValue;
    }

</script>
@endsection
