<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    use HasFactory;

    protected $fillable = [
        'waybill_no',
        'customer_id',
        'service_type',
        'shipper_name',
        'shipper_address',
        'shipper_postcode',
        'shipper_attention',
        'shipper_tel',
        'receiver_name',
        'receiver_address',
        'receiver_postcode',
        'receiver_attention',
        'receiver_tel',
        'order_content',
        'order_category',
        'order_size',
        'order_total_weight',
        'remarks',
    ];
}
