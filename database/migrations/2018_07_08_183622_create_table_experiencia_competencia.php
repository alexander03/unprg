<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExperienciaCompetencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiencia_competencia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calificacion')->nullable();
            $table->integer('competencia_alumno_id')->unsigned()->nullable();
            $table->integer('experiencia_laboral_id')->unsigned()->nullable();
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
        Schema::dropIfExists('experiencia_competencia');
    }
}
