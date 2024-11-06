<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_tipos_actividades', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('sts_emp_departamentos');

		    $table->integer('tipo_proceso_id')->unsigned();
		    $table->foreign('tipo_proceso_id')->references('id')->on('sts_proces_procesos');

		    $table->string('nombre', 45)->nullable();
            $table->string('iniciales', 255)->nullable();
            $table->string('permiso', 255)->nullable()->default('libre');
            $table->string('accion', 255)->nullable();
            $table->string('color', 45)->nullable();
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
        Schema::dropIfExists('sts_emp_tipos_actividades');
    }
}
