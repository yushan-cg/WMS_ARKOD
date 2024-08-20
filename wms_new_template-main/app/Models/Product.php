<?php

namespace App\Models;

use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'product_location')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    // public function productreport()
    // {
    //     return $this->belongsTo(ProductReport::class);
    // }

    // public function deliveries()
    // {
    //     return $this->belongsToMany(Delivery::class, 'delivery_product');
    // }

    // public function pickers()
    // {
    //     return $this->hasMany(Picker::class);
    // }

    // public function returnStocks()
    // {
    //     return $this->belongsToMany(ReturnStock::class, 'pickers');
    // }

}
