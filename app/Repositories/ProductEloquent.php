<?php

namespace App\Repositories;

use App\Models\Product;

class ProductEloquent implements ProductInterface
{
    public function all()
    {
        return Product::select(['id','product_name','trade_price','retail_price','quantity'])
                           ->orderBy('product_name')
                           ->get();;
    }
}
