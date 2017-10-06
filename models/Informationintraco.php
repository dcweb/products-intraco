<?php

namespace Dcms\Productsintraco\Models;

use Dcms\Products\Models\Information;

class Informationintraco extends Information
{
	public function pages()
	{
		return $this->belongsToMany('\Dcms\Pages\Models\Pages', 'pages_to_products_information_group', 'information_id', 'page_id')->withTimestamps();
	}
}
