<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sts_emp_contactos', function (Blueprint $table) {
            //
            $table->increments('id');
		    $table->enum('tipo', ['TELEFONO', 'CORREO', 'MOVIL', 'DIRECCION'])->nullable();
		    $table->string('contacto', 45)->nullable();
            $table->enum('activo', ['si',  'no'])->default('si');            
		    $table->string('folio', 45);
		
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
        Schema::dropIfExists('sts_emp_contactos');
    }
}
