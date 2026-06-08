<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT Sinar Jaya Plastik',
                'alamat' => 'Kawasan Industri Candi Blok A No. 12, Semarang',
                'telepon' => '024-7648392',
                'email' => 'sales@sinarjaya.com',
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'CV Indo Raya Packaging',
                'alamat' => 'Jl. Margomulyo Indah No. 45, Surabaya',
                'telepon' => '031-7483920',
                'email' => 'info@indoraya.co.id',
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'PT Harapan Baru Lestari',
                'alamat' => 'Jl. Daan Mogot KM 15, Kalideres, Jakarta Barat',
                'telepon' => '021-5432109',
                'email' => 'marketing@harapanbaru.com',
            ],
            [
                'kode_supplier' => 'SUP-004',
                'nama_supplier' => 'UD Berkah Mandiri',
                'alamat' => 'Jl. Solo-Yogya KM 10, Klaten',
                'telepon' => '0272-321456',
                'email' => 'berkahmandiri@gmail.com',
            ],
            [
                'kode_supplier' => 'SUP-005',
                'nama_supplier' => 'PT Global Polimer Indonesia',
                'alamat' => 'Kawasan Industri Jababeka Phase II, Cikarang',
                'telepon' => '021-8934567',
                'email' => 'contact@globalpolimer.com',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }
    }
}
