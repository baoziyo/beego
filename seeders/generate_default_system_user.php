<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class GenerateDefaultSystemUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Hyperf\DbConnection\Db::table('user')->insert([
            'id' => 1,
            'name' => 'system',
            'password' => '0',
            'salt' => '0',
            'status' => 'enabled',
            'isAdmin' => 'enabled',
        ]);
    }
}
