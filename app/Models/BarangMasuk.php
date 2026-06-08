<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'product_id',
        'supplier_id',
        'tanggal',
        'jumlah',
        'harga_beli',
        'total_harga',
    ];

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Auto update product stock on model events.
     */
    protected static function booted(): void
    {
        static::created(function ($barangMasuk) {
            $product = $barangMasuk->product;
            if ($product) {
                $product->increment('stok', $barangMasuk->jumlah);
            }
        });

        static::updated(function ($barangMasuk) {
            // Check if product_id was changed
            if ($barangMasuk->wasChanged('product_id')) {
                // Decrement old product stock
                $oldProductId = $barangMasuk->getOriginal('product_id');
                $oldJumlah = $barangMasuk->getOriginal('jumlah');
                $oldProduct = Product::find($oldProductId);
                if ($oldProduct) {
                    $oldProduct->decrement('stok', $oldJumlah);
                }

                // Increment new product stock
                $newProduct = $barangMasuk->product;
                if ($newProduct) {
                    $newProduct->increment('stok', $barangMasuk->jumlah);
                }
            } else {
                // If quantity was changed for same product
                $oldJumlah = $barangMasuk->getOriginal('jumlah');
                $newJumlah = $barangMasuk->jumlah;
                $difference = $newJumlah - $oldJumlah;

                if ($difference !== 0) {
                    $product = $barangMasuk->product;
                    if ($product) {
                        $product->increment('stok', $difference);
                    }
                }
            }
        });

        static::deleted(function ($barangMasuk) {
            $product = $barangMasuk->product;
            if ($product) {
                // Deduct the quantity when record is deleted
                $product->decrement('stok', $barangMasuk->jumlah);
            }
        });
    }
}
