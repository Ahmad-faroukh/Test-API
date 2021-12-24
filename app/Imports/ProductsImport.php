<?php

namespace App\Imports;

use App\Http\Resources\ProductsResource;
use App\Jobs\StoreProductsJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // retrieve and then remove the first row of the csv file (witch would be the column tittles)
        $keys = $collection->shift()->toArray();

        // formats the file object according to Shopify API documentation
        $products = $collection->mapInto(ProductsResource::class)->map->serializeForShopifyAPI();

        StoreProductsJob::dispatch($products);
    }

}
