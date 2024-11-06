<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_departamentos', function (Blueprint $table) {
            //
            $table->increments('id');
		    $table->text('nombre')->nullable();
		    $table->integer('padre_id')->nullable()->default(0);
		    $table->enum('activo', ['si',  'no'])->default('si');
		    
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
        Schema::dropIfExists('sts_emp_departamentos');
    }
}
