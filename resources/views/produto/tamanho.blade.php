
@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
@section('content')
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
<main id="main" class="main">
    <a href="/pedidos" id="botao-canto-superior-direito" class="btn btn-secondary">Voltar</a>
    <div class="pagetitle">
      <h1>Lista de Tamanhos dos Produtos</h1>
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
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Crie um novo Tamanho de Produto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Adicione o formulário aqui -->
                                            <form method="POST" action="{{ route('tamanho.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Nome</label>
                                                                <input type="text" class="form-control" id="" name="nome">
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
                                        <th scope="col">Excluir</th>
                                        <th scope="col">Detalhes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tamanhos as $tamanho)

                                        <tr>
                                            <td scope="row">{{ $tamanho->id }}</td>
                                            <td>{{ $tamanho->nome }}</td>
                                            <td>
                                                <form action="{{ route('tamanho.destroy', $tamanho->id) }}" method="post"
                                                    class="ms-2">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">Excluir</button>

                                                </form>
                                            </td>
                                            <td>
                                                <div>
                                                    <!-- Botão de edição que abre o modal -->
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $tamanho->id }}">
                                                        Editar
                                                    </button>
                                                </div>

                                                <!-- Modal de edição para cada investimento -->
                                                <div class="modal fade" id="editModal{{ $tamanho->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Tipo do Produto</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Formulário de edição para este tamanho -->
                                                                <form method="POST" action="{{ route('tamanho.update', ['id' => $tamanho->id]) }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="">Nome</label>
                                                                                    <input type="text" class="form-control" id="" name="tipo" value="{{ $tamanho->tipo }}">
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
                                            <p class="alert-warning" style="font-size:22px; text-align:center;">Nenhum Tipo Produto Cadastrado</p>

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
      precoValue = precoValue.replace(/\s/g, '').replace('.', ',');

      // Converter para número com duas casas decimais
      precoValue = parseFloat(precoValue).toFixed(2);

      // Atualizar o valor no input
      precoInput.value = precoValue;
    }

    function formatarPreco2() {
      var precoInput = document.getElementById('preco2');
      var precoValue = precoInput.value;

      // Remover espaços em branco e substituir vírgulas por pontos
      precoValue = precoValue.replace(/\s/g, '').replace('.', ',');

      // Converter para número com duas casas decimais
      precoValue = parseFloat(precoValue).toFixed(2);

      // Atualizar o valor no input
      precoInput.value = precoValue;
    }
</script>
@endsection
