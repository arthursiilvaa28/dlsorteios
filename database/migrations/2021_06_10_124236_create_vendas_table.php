<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sorteio_id');
            $table->foreign('sorteio_id')->references('id')->on('sorteios')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->string('cotas');
            $table->integer('qtdCotas');
            $table->decimal('valorTotal', $precision = 8, $scale = 2);

            $table->string('dataReserva');
            $table->string('dataPay');

            $table->string('comprovante');
            $table->enum('status', array('Pendente', 'Análise', 'Completo', 'Recusado', 'Removido'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendas');
    }
}
