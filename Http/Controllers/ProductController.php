<?php
namespace Dcms\Productsintraco\Http\Controllers;

use Dcweb\Dcms\Controllers\BaseController;

use Dcms\Productsintraco\Models\Product;
use Dcms\Productsintraco\Models\Informationintraco as Information;
use Dcms\Products\Models\Attachment;
use Dcms\Products\Models\Price;
use Dcms\Products\Models\Category;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatables;
use Auth;

class ProductController extends \Dcms\Products\Http\Controllers\ProductController {

	function __construct()
	{
		parent::__construct(); //sets the default columnnames, we can extend these doing an array_merge

		$this->productColumNames = array_merge($this->productColumNames,array());
		$this->productColumnNamesDefaults = array_merge($this->productColumnNamesDefaults,array('new'=>'0'));

		$this->informationColumNames  = array_merge($this->informationColumNames,array( ));
		$this->extendgeneralTemplate  = 'dcmsproductsintraco::products/templates/pages';
	}

	public static function getProductInformationByLanguage($language_id = null)
	{
		return Information::where("language_id","=",$language_id)->lists("title","id");
	}

	//return the model to fill the form
	public function getExtendedModel($id = null)
	{
		if (is_null($id)) {
			return array('pageOptionValuesSelected'=>array());
		} else {
			return array('pageOptionValuesSelected'=>$this->setPageOptionValues($this->getSelectedPages($id)));
		}
	}

	public function getSelectedPages($productid = null)
	{
		//Fetch the first and best information group_id
		$Product = Product::with('information')->find($productid);

		$Information = $Product->information;
		$information_group_id = null;
		$information_id = null;
		$aInformation_id = array();
		if (count($Information)>0) {
			foreach ($Information as $I) {
				if (!is_null($I->information_group_id)) {
						$information_id = $I->id;
						$aInformation_id[] = $I->id;
						$information_group_id = $I->information_group_id;
						//break;
				}
			}
		}

		return DB::connection("project")->select('  SELECT information_group_id, page_id
													FROM pages_to_products_information_group
													WHERE
                                                    information_group_id = ?
                                                    AND
                                                    information_id IN ('.implode(',',$aInformation_id).')', array($information_group_id));
	}

	public function setPageOptionValues($objselected_pages)
	{
		$pageOptionValuesSelected = array();
		if (count($objselected_pages)>0) {
			foreach($objselected_pages as $obj) {
				$pageOptionValuesSelected[$obj->page_id] = $obj->page_id;
			}
		}
		return $pageOptionValuesSelected;
	}

	public function saveProductToPage($Productref) {
			$input = Input::get();

            $Product = \Dcms\Productsintraco\Models\Product::find($Productref->id);
            
			$Information = $Product->information;

			if(count($Information)>0) {
				foreach($Information as $I) {
					if(!is_null($I->information_group_id)) {
						$I->pages()->detach(); //->wherePivot('information_group_id','=',$I->information_group_id); //

						if (isset($input["page_id"]) && count($input["page_id"])>0) {
							foreach ($input["page_id"] as $language_id => $thepages) {
								foreach($thepages as $p => $page_id) {
                                    $I->pages()->attach($page_id,array('information_group_id'=>$I->information_group_id));
                                }
							}
							break;
						}
					}
				}
			}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateProductForm() === true)
		{
			$Product = $this->saveProductProperties();
			$this->saveProductInformation($Product);
			$this->saveProductPrice($Product);
			$this->saveProductAttachments($Product);
			$this->saveProductToPage($Product);

			// redirect
			Session::flash('message', 'Successfully created Product!');
			return Redirect::to('admin/products');
		}else return  $this->validateProductForm();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if (parent::validateProductForm()===true) {
			$Product = $this->saveProductProperties($id);
			$this->saveProductInformation($Product);
			$this->saveProductPrice($Product);
			$this->saveProductAttachments($Product);
			$this->saveProductToPage($Product);

			// redirect
			Session::flash('message', 'Successfully updated Product!');
			return Redirect::to('admin/products');
		}else return  $this->validateProductForm();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		parent::destroy($id);

		// redirect
		Session::flash('message', 'Successfully deleted the Product!');
		return Redirect::to('admin/products');
	}
}
