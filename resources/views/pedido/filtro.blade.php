@extends('layouts.app')
<style>
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Optional: Set a specific height to center vertically */
}
/* Estilo para o botão no canto superior direito */
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
<a href="/pedidos" id="botao-canto-superior-direito" class="btn btn-secondary">Voltar</a>

    <div class="form-container">

            <form action="/relatorio" method="post">
                @csrf
                <label for="data_inicial">Data Inicial:</label>
                <input class="form-control" type="date" name="data_inicial" required>

                <label for="data_final">Data Final:</label>
                <input class="form-control" type="date" name="data_final" required>

                <label for="status">Status:</label>
                <select class="form-control" name="status">
                    <option value="">Todos</option>
                    <option value="1">Em Andamento</option>
                    <option value="2">Feito</option>
                    <option value="3">Deletado</option>
                    <!-- Adicione mais opções conforme necessário -->
                </select>
                <label for="pago">Pago:</label>
                <select class="form-control" name="pago">
                    <option value="">Todos</option>
                    <option value="S">Sim</option>
                    <option value="N">Não</option>
                    <!-- Adicione mais opções conforme necessário -->
                </select>
                <br>
                <button class="btn btn-danger" type="submit">Gerar Relatório</button>
            </form>
    </div>
@endsection

