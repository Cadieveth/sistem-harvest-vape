<?php

namespace App\Rules;

use App\Models\Inventory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InventoryCheck implements ValidationRule
{
    protected $kode_barang;

    public function __construct($kode_barang)
    {
        $this->kode_barang = $kode_barang;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        \Log::info('Kode Barang: ' . $this->kode_barang); // Menambahkan log untuk debugging
        $inventory = Inventory::where('kode_barang', $this->kode_barang)->first();

        if (!$inventory) {
            \Log::info('Inventory tidak ditemukan'); // Menambahkan log untuk debugging
        } else {
            \Log::info('Jumlah Barang: ' . $inventory->jumlah_barang); // Menambahkan log untuk debugging
        }

        if (!$inventory || $inventory->jumlah_barang < $value) {
            $fail('Stok tidak cukup');
        }
    }
}
