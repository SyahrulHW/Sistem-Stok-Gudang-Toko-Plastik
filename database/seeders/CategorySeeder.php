<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Plastik Kresek', 'deskripsi' => 'Kantong plastik belanja berbagai ukuran dan warna.'],
            ['nama_kategori' => 'Plastik PP', 'deskripsi' => 'Plastik PP bening, kaku, dan mengkilap. Cocok untuk kemasan makanan kering.'],
            ['nama_kategori' => 'Plastik HDPE', 'deskripsi' => 'Plastik High Density Polyethylene, kuat dan tahan panas.'],
            ['nama_kategori' => 'Plastik PE', 'deskripsi' => 'Plastik Polyethylene lentur, ulet, cocok untuk kemasan es atau cairan.'],
            ['nama_kategori' => 'Sedotan', 'deskripsi' => 'Sedotan minuman berbagai jenis (sedotan ulir, bubble, steril).'],
            ['nama_kategori' => 'Gelas Plastik', 'deskripsi' => 'Gelas plastik cup bening berbagai ukuran (12oz, 14oz, 16oz, 22oz).'],
            ['nama_kategori' => 'Mika & Box', 'deskripsi' => 'Kotak makan plastik mika, sterofoam, dan thinwall.'],
            ['nama_kategori' => 'Sendok & Garpu', 'deskripsi' => 'Peralatan makan plastik sekali pakai.'],
            ['nama_kategori' => 'Kertas Minyak', 'deskripsi' => 'Kertas pembungkus nasi dan makanan tahan air/minyak.'],
            ['nama_kategori' => 'Karet Gelang', 'deskripsi' => 'Karet gelang pengikat berbagai warna dan kualitas.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
