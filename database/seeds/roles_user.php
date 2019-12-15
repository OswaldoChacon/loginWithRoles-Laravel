<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class roles_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles_user')->insert([
            'role_id'=>1,
            'user_id'=>1
        ]);
    }
}
