<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //USER

        DB::table('users')->insert([
            'name' => 'Client',
            'tipo' => 'Client',
            'email' => 'clientes@lista.com',
            'password' => bcrypt('12345678'),
        ]);

        //STATUS

        DB::table('status')->insert([
            'status' => 'EM ANDAMENTO',
        ]);

        DB::table('status')->insert([
            'status' => 'FEITO',
        ]);

        DB::table('status')->insert([
            'status' => 'DELETADO',
        ]);

        //FORMA PAGAMENTO
        DB::table('forma_pagamento')->insert([
            'forma' => 'PIX',
        ]);

        DB::table('forma_pagamento')->insert([
            'forma' => 'DEBITO',
        ]);

        DB::table('forma_pagamento')->insert([
            'forma' => 'CREDITO',
        ]);

        DB::table('forma_pagamento')->insert([
            'forma' => 'DINHEIRO',
        ]);

        //Tipo
        DB::table('tipo_prod')->insert([
            'tipo' => 'Salgados',
        ]);

        DB::table('tipo_prod')->insert([
            'tipo' => 'Bebidas',
        ]);

        //PRODUTOS
        DB::table('produto')->insert([
            'nome' => 'Camarão c/ Queijo',
            'preco' => '16.00',
            'descricao' => '8 UND',
            'imagem' => 'CamaraoQueijo.jpg',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Kibe',
            'preco' => '15.00',
            'descricao' => '12 UND',
            'imagem' => 'Kibe.jpg',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Camarão na Tapioca',
            'preco' => '18.00',
            'descricao' => '8 UND',
            'imagem' => 'CamaraoTapioca.jpg',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Camarão Empanado',
            'preco' => '15.00',
            'descricao' => '8 UND',
            'imagem' => 'CamaraoEmpanado.jpg',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Coxinha',
            'preco' => '15.00',
            'descricao' => '12 UND',
            'imagem' => 'Coxinha.jpg',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Sortidos',
            'preco' => '20.00',
            'descricao' => '4 Kibes, 4 Coxinhas, 2 Encapotado, 2 na Tapioca',
            'imagem' => 'Sortidos.png',
            'tipo' => 1,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Guarana Antarctica',
            'preco' => '2.00',
            'descricao' => '200 ML',
            'imagem' => 'Guarana.jpg',
            'tipo' => 2,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Pepsi',
            'preco' => '2.00',
            'descricao' => '200 ML',
            'imagem' => 'Pepsi.jpg',
            'tipo' => 2,
        ]);

        DB::table('produto')->insert([
            'nome' => 'Coca-Cola',
            'preco' => '6.00',
            'descricao' => '1 Litro',
            'imagem' => 'Cocacola.jpg',
            'tipo' => 2,
        ]);

        //Empresa
        DB::table('empresas')->insert([
            'nome' => 'Camarão da Praça',
            'imagem' => 'images/logo2.png',
        ]);

    }
}
