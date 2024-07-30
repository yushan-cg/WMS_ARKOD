<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'invoice_no',
    //     'customer_id',
    //     'payment_method',
    //     'company_name',//in client db it 'name'
    //     'attention',
    //     'address',
    //     'tel',
    //     'payment_terms',
    //     'due_date',
    //     'remarks',
    // ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
