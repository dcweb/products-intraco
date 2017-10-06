<?php

namespace Dcms\Productsintraco\Models;

use  Dcms\Products\Models\Product as DcmsProduct;

use Auth;

class Product extends DcmsProduct
{
	public function information()
    {
        return $this->belongsToMany('\Dcms\Productsintraco\Models\Informationintraco', 'products_to_products_information', 'product_id', 'product_information_id')->withTimestamps();
    }
}
