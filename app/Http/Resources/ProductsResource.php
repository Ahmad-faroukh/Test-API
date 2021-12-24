<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->cleanArray(parent::toArray($request));

        return $data;
    }

    // formats the file object according to Shopify API documentation
    public function serializeForShopifyAPI(){
        $data = parent::toArray($this);
        return [
          'product'=>[
              'handle'=>$data[0],
              'title'=>$data[1],
              'body_html'=>$data[2],
              'vendor'=>$data[3],
              'product_type'=>$data[4],
              'tags'=>$data[5],
              'published'=>$data[6],
              'options'=>[
                  [
                      'name'=>$data[7],
                      'values'=>[
                          $data[8]
                      ],
                  ],
              ],
              'variants'=>[
                  [
                      'sku'=>$data[13],
                      'grams'=>$data[14],
                      'inventory_quantity'=>$data[16],
                      'inventory_policy'=>$data[17],
                      'fulfillment_service'=>$data[18],
                      'price'=>$data[19],
                      'compare_at_price'=>$data[20],
                      'requires_shipping'=>$data[21],
                      'taxable'=>$data[22],
                      'barcode'=>$data[23],
                      'weight_unit'=>$data[44],
                  ]
              ],
              'images'=>[
                  [
                      'src'=>$data[24],
                      'position'=>$data[25],
                      'alt'=>$data[26],
                  ]
              ],
          ]
        ];
    }

    // helper function that takes an input array an cleans it up recursively according to the rules below
    function cleanArray($input){
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->cleanArray($value);
            }
        }
        return array_filter($input, function($val){
            // input cleaning rules
            if ($val != null && $val != "" && $val != "N/A" && $val != "-"){
                return  $val;
            }
        } );
    }
}
