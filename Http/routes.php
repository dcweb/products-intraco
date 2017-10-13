<?php



Route::group(['middleware' => ['web']], function () {

	Route::group( array("prefix" => "admin", "as"=>"admin."), function() {

    	Route::group(['middleware' => 'auth:dcms'], function() {

    		//PRODUCTS
    		Route::group( array("prefix" => "products", "as"=>"products."), function() {

    			//API
    			Route::group( array("prefix" => "api","as"=>"api."), function() {
    				Route::any("table", array("as"=>"table", "uses" => "ProductController@getDatatable"));
    				Route::any("tablerow", array("as"=>"tablerow", "uses" => "ProductController@getTableRow"));
    				Route::get("pim", array("as"=>"pim", "uses" => "ProductController@json"));
    				Route::get("xls", array("as"=>"xls", "uses" => "ProductController@json"));
    				Route::any("relation/table/{article_id?}", array("as"=>"relation.table", "uses" => "ProductController@getRelationDatatable"));
    			});
    			Route::get("{id}/copy/{country_id?}", array("as"=>"{id}.copy.{country_id}", "uses" => "ProductController@copy"));
    		});
    		Route::resource("products","ProductController");
    });
  });
});



 ?>
