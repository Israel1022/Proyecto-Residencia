<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_proces_detalles', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('movimiento_id')->unsigned();
            $table->foreign('movimiento_id')->references('id')->on('sts_proces_movimientos');
                
		    $table->json('descripcion')->nullable();
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
        Schema::table('sts_proces_detalles', function (Blueprint $table) {
            //
        });
    }
}
