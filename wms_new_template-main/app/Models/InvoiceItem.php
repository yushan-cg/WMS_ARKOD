<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // protected $table = 'invoice_items';


    // protected $fillable = ['invoice_id', 'quantity', 'description', 'unit_price', 'total_price', 'subtotal_price', 'sst', 'final_price'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
