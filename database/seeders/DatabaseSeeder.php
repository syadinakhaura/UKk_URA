<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Siswa Test',
            'nisn' => '1234567890',
            'email' => 'siswa@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);

        User::factory()->create([
            'name' => 'Yayasan',
            'email' => 'yayasan@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'yayasan',
        ]);

        $categories = ['Sarana Kelas', 'Laboratorium', 'Toilet', 'Perpustakaan', 'Olahraga', 'Lainnya'];
        foreach ($categories as $category) {
            \App\Models\Kategori::create(['nama_kategori' => $category]);
        }
    }
}
