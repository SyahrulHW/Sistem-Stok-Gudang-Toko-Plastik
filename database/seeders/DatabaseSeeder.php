<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default Admin
        User::create([
            'name' => 'Admin Gudang Plastik',
            'email' => 'admin@plastik.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Seed default Karyawan
        User::create([
            'name' => 'Karyawan Gudang',
            'email' => 'karyawan@plastik.com',
            'password' => bcrypt('password'),
            'role' => 'karyawan',
        ]);

        // Call individual seeders
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            BarangMasukSeeder::class,
            BarangKeluarSeeder::class,
        ]);
    }
}
