<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventoAlumno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Evento_Alumno', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('alumno_id')->unsigned();
            //$table->foreign('alumno_id')->references('id')->on('alumno')->onUpdate('restrict')->onDelete('restrict');
            $table->integer('evento_id')->unsigned();
            //$table->foreign('evento_id')->references('id')->on('evento')->onUpdate('restrict')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Evento_Alumno');
    }
}
