<?php

use App\Rol;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Roles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellidoP')->nullable();
            $table->string('apellidoM')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('num_control')->unique()->nullable();
            $table->string('cod_confirmacion')->nullable();
            $table->timestamp('email_verificado_at')->nullable();
            $table->boolean('confirmado')->default(false);
            $table->rememberToken();
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

        Schema::dropIfExists('users');
    }   
}
