<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_proces_movimientos', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('padre_id')->default(0);            
            $table->string('folio', 45);

		    $table->integer('estatus_id')->unsigned();		
		    $table->foreign('estatus_id')->references('id')->on('sts_proces_estatus');

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
        Schema::dropIfExists('sts_proces_movimientos');
    }
}
