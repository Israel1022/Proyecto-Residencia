<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_configuraciones', function (Blueprint $table) {
            $table->increments('id');
		    $table->text('nombre')->nullable();
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
        Schema::dropIfExists('sts_emp_configuraciones');
    }
}