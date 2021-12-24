<?php

namespace App\Http\Controllers\ShopifyAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsImportRequest;
use App\Http\Resources\ProductsResource;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index(){

        $api_key = env('SHOPIFY_API_KEY', '');
        $api_password = env('SHOPIFY_API_PASSWORD', '');
        $shop = env('SHOPIFY_STORE_NAME', '');
        $response = Http::get("https://$api_key:$api_password@$shop.myshopify.com/admin/products.json?created_at_min=2021-12-24");

        $products = json_decode($response->body(),true);

        return ProductsResource::collection($products['products']);
    }

    public function importCSV(ProductsImportRequest $request){
        try{
            Excel::import(new ProductsImport, $request->file('productsCSV'));
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e){
            return response()->json($e);
        }

        return response()->json('Products imported successfully',200);
    }
}