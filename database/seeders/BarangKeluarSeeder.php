<?php

namespace Database\Seeders;

use App\Models\BarangKeluar;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BarangKeluarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            // Retrieve products that have a stock greater than 15
            $product = Product::where('stok', '>', 15)->inRandomOrder()->first();

            // If no product is found (unlikely), fallback to any product and ensure it has stock
            if (!$product) {
                $product = Product::inRandomOrder()->first();
                if ($product && $product->stok < 20) {
                    $product->stok = 50;
                    $product->save();
                }
            }

            if ($product) {
                $jumlah = rand(5, 15);

                BarangKeluar::create([
                    'nomor_transaksi' => 'BK-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'product_id' => $product->id,
                    'tanggal' => Carbon::now()->subDays(rand(1, 9))->format('Y-m-d'),
                    'jumlah' => $jumlah,
                    'keterangan' => 'Penjualan retail harian toko plastik.',
                ]);
            }
        }
    }
}
