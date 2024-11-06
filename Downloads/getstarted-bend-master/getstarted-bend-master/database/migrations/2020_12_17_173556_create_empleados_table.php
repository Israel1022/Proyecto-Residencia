<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_empleados', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('departamento_id')->unsigned();
		    $table->foreign('departamento_id')->references('id')->on('sts_emp_departamentos');

            $table->string('cve_empleado', 45)->nullable();
		    $table->string('nombres', 255)->nullable();
		    $table->string('apellidos', 255)->nullable();
            $table->json('caracteristicas')->nullable();
		    $table->enum('activo', ['si',  'no'])->default('si');
            
		    $table->string('folio', 45);
		    //$table->foreign('folio')->references('folio')->on('sts_emp_contactos');
		
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
        Schema::dropIfExists('sts_emp_empleados');
    }
}
