<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'nombre'  => 'oswaldo',
                'apellidoP' => 'chacon',
                'apellidoM' => 'perez',
                'email'     => 'oswaldo@gmail.com',
                'password' => bcrypt('oswaldo'), // Hash::make('oswaldo') // Hash::make() nos va generar una cadena con nuestra contraseña encriptada
                'num_control' => '15270717',
                'confirmado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre'  => 'jose',
                'apellidoP' => 'chacon',
                'apellidoM' => 'perez',
                'email'     => 'jose@gmail.com',
                'password' => bcrypt('oswaldo'), // Hash::make('oswaldo') // Hash::make() nos va generar una cadena con nuestra contraseña encriptada
                'num_control' => '15270718',
                'confirmado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre'  => 'luis',
                'apellidoP' => 'chacon',
                'apellidoM' => 'perez',
                'email'     => 'luis@gmail.com',
                'password' => bcrypt('oswaldo'), // Hash::make('oswaldo') // Hash::make() nos va generar una cadena con nuestra contraseña encriptada
                'num_control' => '15270719',
                'confirmado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre'  => 'pedro',
                'apellidoP' => 'chacon',
                'apellidoM' => 'perez',
                'email'     => 'pedro@gmail.com',
                'password' => bcrypt('oswaldo'), // Hash::make('oswaldo') // Hash::make() nos va generar una cadena con nuestra contraseña encriptada
                'num_control' => '15270720',
                'confirmado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
