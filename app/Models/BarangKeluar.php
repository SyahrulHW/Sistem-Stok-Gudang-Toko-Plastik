<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'product_id',
        'tanggal',
        'jumlah',
        'keterangan',
    ];

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Auto update product stock on model events.
     */
    protected static function booted(): void
    {
        static::created(function ($barangKeluar) {
            $product = $barangKeluar->product;
            if ($product) {
                $product->decrement('stok', $barangKeluar->jumlah);
            }
        });

        static::updated(function ($barangKeluar) {
            // Check if product_id was changed
            if ($barangKeluar->wasChanged('product_id')) {
                // Increment old product stock
                $oldProductId = $barangKeluar->getOriginal('product_id');
                $oldJumlah = $barangKeluar->getOriginal('jumlah');
                $oldProduct = Product::find($oldProductId);
                if ($oldProduct) {
                    $oldProduct->increment('stok', $oldJumlah);
                }

                // Decrement new product stock
                $newProduct = $barangKeluar->product;
                if ($newProduct) {
                    $newProduct->decrement('stok', $barangKeluar->jumlah);
                }
            } else {
                // If quantity was changed for same product
                $oldJumlah = $barangKeluar->getOriginal('jumlah');
                $newJumlah = $barangKeluar->jumlah;
                $difference = $newJumlah - $oldJumlah;

                if ($difference !== 0) {
                    $product = $barangKeluar->product;
                    if ($product) {
                        // If quantity increased, we subtract difference from stock.
                        // If quantity decreased, we add difference (which will be negative, so decrement negative = increment).
                        $product->decrement('stok', $difference);
                    }
                }
            }
        });

        static::deleted(function ($barangKeluar) {
            $product = $barangKeluar->product;
            if ($product) {
                // Restore the stock when transaction is deleted
                $product->increment('stok', $barangKeluar->jumlah);
            }
        });
    }
}
