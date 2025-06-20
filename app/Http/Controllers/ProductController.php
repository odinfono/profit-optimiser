<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{

    public function __construct(protected ProductInterface $product_interface) {}
    
    public function index(): AnonymousResourceCollection
    {
        $products = $this->product_interface->all();

        return ProductResource::collection($products);
    }
}
