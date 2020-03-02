<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTiposTablaProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            //
            $table->unsignedInteger('tipos_proyectos_id')->after('lineadeinvestigacion_id');
            $table->foreign('tipos_proyectos_id')->references('id')->on('tipos_de_proyectos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            //
            $table->dropColumn('tipos_proyectos_id');
        });
    }
}
