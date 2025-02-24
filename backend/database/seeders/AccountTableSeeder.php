<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->insert([
            // admin権限を持つユーザー
            'name' => 'test1',
            'email' => 'test1@example.com',
            'is_admin' => true,
            'password' => bcrypt('test1'),
            'birthday' => '1998-07-25'
        ]);
        DB::table('accounts')->insert([
            // admin権限を持たないユーザー
            'name' => 'test2',
            'email' => 'test2@example.com',
            'is_admin' => false,
            'password' => bcrypt('test2'),
            'birthday' => '1998-03-15'
        ],);
    }
}
