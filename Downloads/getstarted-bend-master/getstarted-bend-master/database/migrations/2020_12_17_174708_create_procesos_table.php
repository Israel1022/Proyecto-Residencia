<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_proces_configuracion', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('tipo_proceso_id')->unsigned();
            $table->foreign('tipo_proceso_id')->references('id')->on('sts_proces_procesos');
            
            $table->integer('estatus_actual_id')->unsigned();
            $table->foreign('estatus_actual_id')->references('id')->on('sts_proces_estatus');
            
            $table->integer('estatus_final_id')->unsigned();
		    $table->foreign('estatus_final_id')->references('id')->on('sts_proces_estatus');
		    $table->string('accion', 255)->nullable();
            $table->string('permiso', 255)->nullable()->default('libre');
            $table->boolean('notificar');
            
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
        Schema::dropIfExists('sts_proces_configuracion');
    }
}
