<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoliadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_foliadores', function (Blueprint $table) {
            //
            $table->increments('id');
		    $table->enum('activo', ['si',  'no'])->default('si');
		    $table->string('exprecion', 255)->nullable();
		    $table->string('folio', 255)->nullable();
		    $table->string('ultimofolio', 45)->nullable();
		    $table->enum('tipo_folio', ['SECUENCIAL',  'EXPRECION_REGULAR'])->nullable();
		    // $table->string('foliadorescol', 45)->nullable();

            $table->integer('empresa_id')->unsigned();
		    $table->foreign('empresa_id')->references('id')->on('sts_emp_empresas');
		
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
        Schema::dropIfExists('sts_emp_foliadores');
    }
}
