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
            [
                'roles_id' => 1,
                'user_id' => 1
            ],
            [
                'roles_id' => 2,
                'user_id' => 1
            ],
            [
                'roles_id' => 3,
                'user_id' => 1
            ],
            [
                'roles_id' => 2,
                'user_id' => 2
            ],
            [
                'roles_id' => 3,
                'user_id' => 3
            ],
            [
                'roles_id' => 3,
                'user_id' => 4
            ]
        ]);
    }
}
