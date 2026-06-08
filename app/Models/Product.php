<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'category_id',
        'supplier_id',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'minimum_stok',
        'foto',
        'deskripsi',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that provides the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the incoming goods for this product.
     */
    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Get the outgoing goods for this product.
     */
    public function barangKeluars(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Helper to get the image URL.
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-product.png');
    }

    /**
     * Check if stock is low.
     */
    public function isLowStock(): bool
    {
        return $this->stok <= $this->minimum_stok;
    }
}
