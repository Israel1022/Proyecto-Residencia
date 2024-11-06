<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_rol_usuario', function (Blueprint $table) {
            //
            $table->increments('id');
                
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('sts_emp_usuarios');
            
		    $table->integer('rol_id')->unsigned();
            $table->foreign('rol_id')->references('id')->on('sts_emp_roles');
            
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
        Schema::dropIfExists('sts_emp_rol_usuario');
    }
}
