<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_usuarios', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('empleado_id')->unsigned();
		    $table->foreign('empleado_id')->references('id')->on('sts_emp_empleados');

		    $table->string('usuario', 255)->nullable();
		    $table->string('password', 255)->nullable();
            $table->string('email',255)->nullable();
		    $table->dateTime('email_verified_at')->nullable();
		    $table->string('remember_token', 100)->nullable();
		    $table->string('api_token', 60)->nullable();
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
        Schema::table('sts_emp_usuarios', function (Blueprint $table) {
            //
        });
    }
}
