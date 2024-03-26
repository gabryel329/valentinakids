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
      <h1>Dados da Empresa</h1>
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
                                            <h5 class="modal-title">Crie os dados da Empresa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Adicione o formulário aqui -->
                                            <form method="POST" action="{{ route('empresa.store') }}" enctype="multipart/form-data">
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
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                            <label for="">Imagem</label>
                                                                <input class="form-control" type="file" name="imagem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Telefone</label>
                                                                <input type="text" name="telefone" class="form-control" placeholder="XXXXXXXXXXX" maxlength="11"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                            <label for="">E-mail</label>
                                                            <input type="email" class="form-control" id="" name="email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Dias de Funcionamento</label>
                                                                <input type="text" class="form-control" id="" name="dias" placeholder="Quinta á Domingo">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                            <label for="">Horário de Funcionamento</label>
                                                            <input type="text" class="form-control" id="" name="horario" placeholder="18h ás 23:30h">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Whatsapp (S/N)</label>
                                                                <input type="text" class="form-control" id="whats" name="whats" oninput="disableInput()" maxlength="1" placeholder="S/N">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">Nº Whatsapp</label>
                                                                <input type="text" name="whats_number" id="whats_number" maxlength="11" class="form-control" placeholder="XXXXXXXXXXX"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Link Instagram</label>
                                                                <input type="text" class="form-control" id="" name="instagram" placeholder="https://www.instagram.com/username/">
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
                                        <th scope="col">Telefone</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Dias</th>
                                        <th scope="col">Horário</th>
                                        <th scope="col">Excluir</th>
                                        <th scope="col">Detalhes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($empresas as $empresa)

                                        <tr>
                                            <td scope="row">{{ $empresa->id }}</td>
                                            <td>{{ $empresa->nome }}</td>
                                            <td>{{ $empresa->telefone }}</td>
                                            <td>{{ $empresa->email }}</td>
                                            <td>{{ $empresa->dias }}</td>
                                            <td>{{ $empresa->horario }}</td>
                                            <td>
                                                <form action="{{ route('empresa.destroy', $empresa->id) }}" method="post"
                                                    class="ms-2">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">Excluir</button>

                                                </form>
                                            </td>
                                            <td>
                                                <div>
                                                    <!-- Botão de edição que abre o modal -->
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $empresa->id }}">
                                                        Editar
                                                    </button>
                                                </div>

                                                <!-- Modal de edição para cada investimento -->
                                                <div class="modal fade" id="editModal{{ $empresa->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Dados da Empresa</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Formulário de edição para este tipoProd -->
                                                                <form method="POST" action="{{ route('empresa.update', ['id' => $empresa->id]) }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="">Nome</label>
                                                                                    <input type="text" class="form-control" id="" name="nome" value="{{ $empresa->nome }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="">Imagem</label>
                                                                                    <input type="file" class="form-control" id="" name="imagem" value="{{ $empresa->imagem }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Telefone</label>
                                                                                    <input type="text" name="telefone" class="form-control" maxlength="11" value="{{ $empresa->telefone }}"/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                <label for="">E-mail</label>
                                                                                <input type="email" class="form-control" id="" name="email" value="{{ $empresa->email }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Dias de Funcionamento</label>
                                                                                    <input type="text" class="form-control" id="" name="dias" value="{{ $empresa->dias }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                <label for="">Horário de Funcionamento</label>
                                                                                <input type="text" class="form-control" id="" name="horario" value="{{ $empresa->horario }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Whatsapp (S/N)</label>
                                                                                    <input type="text" class="form-control" id="" maxlength="1" name="whats" value="{{ $empresa->whats }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="">Nº Whatsapp</label>
                                                                                    <input type="text" name="whats_number" class="form-control" maxlength="11" value="{{ $empresa->whats_number }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="">Link Instagram</label>
                                                                                    <input type="text" class="form-control" id="" name="instagram" value="{{ $empresa->instagram }}">
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
                                            <p class="alert-warning" style="font-size:22px; text-align:center;">Nenhum Dados da Empresa Cadastrado</p>

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

    $(document).ready(function() {
        // Máscara para CPF
        $('input[name="cpf"]').inputmask("999.999.999-99", { showMaskOnHover: false });

        // Máscara para telefone
        $('input[name="telefone"]').inputmask("(99)99999-9999", { showMaskOnHover: false });
    });

    function disableInput() {
        var input1Value = document.getElementById('whats').value;
        var input2Element = document.getElementById('whats_number');

        // Se o valor de input1 for 'N', desabilita input2, caso contrário, habilita.
        if (input1Value.toUpperCase() === 'N') {
            input2Element.disabled = true;
        } else {
            input2Element.disabled = false;
        }
    }
</script>
@endsection
