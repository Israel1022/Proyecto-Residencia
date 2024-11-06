<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_departamento_usuario', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('sts_emp_usuarios');
            
		    $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('sts_emp_departamentos');
            
            $table->enum('activo', ['si',  'no'])->default('si');

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
        Schema::dropIfExists('sts_emp_departamento_usuario');
    }
}
