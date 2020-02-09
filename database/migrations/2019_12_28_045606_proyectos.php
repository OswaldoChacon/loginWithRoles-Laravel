<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Proyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('folio')->unique();
            $table->string('titulo');
            $table->string('empresa');
            $table->string('objetivo');                        
            $table->unsignedInteger('lineadeinvestigacion_id');
            $table->foreign('lineadeinvestigacion_id')->references('id')->on('lineasdeinvestigacion')->onDelete('cascade');            
            $table->unsignedInteger('foros_id');
            $table->foreign('foros_id')->references('id')->on('foros')->onDelete('cascade');
            $table->unsignedInteger('asesor')->nullable();
            $table->foreign('asesor')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('participa')->default(false);
            $table->boolean('aceptado')->default(false);
            $table->boolean('cancelado')->default(false);
            $table->integer('calificacion_foro')->default(0);
            $table->integer('calificacion_seminario')->default(0);
            $table->integer('promedio')->default(0);
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
        Schema::dropIfExists('proyectos');
    }
}

