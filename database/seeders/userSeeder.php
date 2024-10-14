<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'username' => 'admin',
                'password' => bcrypt('admin123'), // Ganti dengan password yang di-hash
                'token' => Str::random(10),
                'nama' =>  'admin',
                'subidang' =>  'admin',
                'IdPegawai' =>  '0000000001',
                'role' => 'admincs',
                'active_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'username' => 'dev-pegawai1',
                'password' => bcrypt('password123'), // Ganti dengan password yang di-hash
                'token' => Str::random(10),
                'nama' =>  'Jaki Daniyudin',
                'subidang' =>  'Gizi',
                'IdPegawai' =>  '00088812322',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'username' => 'dev-pegawai2',
                'password' => bcrypt('password123'),
                'token' => Str::random(10),
                'nama' =>  'Daviq Risqi',
                'subidang' =>  'Obat',
                'IdPegawai' =>  '00088812312',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'username' => 'dev-pegawai3',
                'password' => bcrypt('password123'),
                'token' => Str::random(10),
                'nama' =>  'Wahyu Sahri',
                'subidang' =>  'Humas',
                'IdPegawai' =>  '00088812334',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
