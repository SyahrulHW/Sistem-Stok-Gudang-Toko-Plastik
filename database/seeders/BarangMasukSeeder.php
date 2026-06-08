<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BarangMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        for ($i = 1; $i <= 30; $i++) {
            // Get a random product
            $product = $products->random();
            $jumlah = rand(30, 100);
            $hargaBeli = $product->harga_beli;

            BarangMasuk::create([
                'nomor_transaksi' => 'BM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'product_id' => $product->id,
                'supplier_id' => $product->supplier_id,
                'tanggal' => Carbon::now()->subDays(rand(10, 30))->format('Y-m-d'),
                'jumlah' => $jumlah,
                'harga_beli' => $hargaBeli,
                'total_harga' => $jumlah * $hargaBeli,
            ]);
        }
    }
}
