<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;


    /**
	 * Get Subcategories by category ID
	 *
	 * @param $category_id category id
	 *
	 * @return array
	 */
    protected function getSubcategories($category_id)
	{
        $parent_category = app('firebase.firestore')->database()->collection('category')->document($category_id);
		$subcategoryList = $parent_category->collection('subcategory')->documents();
		$subcategories = [];
		if (!empty($subcategoryList)) {
			foreach ($subcategoryList as $subcategory) {
				$subcategories[$subcategory->id()] = $subcategory->data()['name'];
			}
        }
		return $subcategories;
	}
}
