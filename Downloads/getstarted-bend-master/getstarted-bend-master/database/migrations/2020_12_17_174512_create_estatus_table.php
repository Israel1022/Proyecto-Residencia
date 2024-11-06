<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_proces_estatus', function (Blueprint $table) {
            //
            $table->increments('id');
		    $table->string('nombre', 255)->nullable();
		    $table->string('estatus', 45)->nullable();
		    $table->json('formulario')->nullable();
		    $table->enum('activo', ['si',  'no'])->default('si');
            $table->string('color', 45)->nullable();
            
		    $table->integer('empresa_id')->unsigned();		
		    $table->foreign('empresa_id')->references('id')->on('sts_emp_empresas');

		    $table->integer('tipo_actividad')->unsigned();
		    $table->foreign('tipo_actividad')->references('id')->on('sts_emp_tipos_actividades');
            
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
        Schema::dropIfExists('sts_proces_estatus');
    }
}