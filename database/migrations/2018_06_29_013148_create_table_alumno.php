<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAlumno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno', function (Blueprint $table) {
            $table->increments('id');
            $table->char('codigo',9)->nullable();
            $table->string('nombres',100)->nullable();
            $table->string('apellidomaterno',100)->nullable();
            $table->string('apellidopaterno',100)->nullable();
            $table->char('dni',11)->nullable();
            $table->date('fechanacimiento')->nullable();
            $table->string('direccion',120)->nullable();
            $table->string('telefono',15)->nullable();
            $table->string('email',30)->nullable();
            $table->integer('escuela_id')->unsigned()->nullable();
            $table->integer('especialidad_id')->unsigned()->nullable();
            $table->char('situacion',2)->nullable();
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
        Schema::dropIfExists('alumno');
    }
}
