<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class shopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected    = "";
        $subCategorySelected = "";

        $brandsArray = [];

        $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands     = Brand::orderBy('name','ASC')->where('status',1)->get();
        
        $products   = Product::where('status',1);

        // Apply Filter Here
        if (!empty($categorySlug)) {
            $category = Category::where('slug',$categorySlug)->first();
            $products  = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = subCategory::where('slug',$subCategorySlug)->first();
            $products  = $products->where('subCategory_id',$subCategory->id);
            $subCategorySelected = $subCategory->id;

        }

        if (!empty($request->get('brands'))) {
            $brandsArray = explode(',',$request->get('brands'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }
        if ($request->get('price_max') != '' && $request->get('price_min') != '' ) {
            if ($request->get('price_max') == 1000) {
            $products = $products->whereBetween('price',[intval($request->get('price_min')),10000000]);
            }else {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
            }
            $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
        }
        $priceMax = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $priceMin = intval($request->get('price_min'));

        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id','DESC');
            }
                elseif ($request->get('sort') == 'price_asc') {
                    $products = $products->orderBy('id','ASC');
            }
                else {
                    $products = $products->orderBy('price','ASC');
            }
        }else {
            $products = $products->orderBy('id','DESC');
        }


        $products = $products->paginate(6);
        
        // return $brandsArray;
        
        return view('Front.shop',compact(['categories','brands','products','categorySelected','subCategorySelected','brandsArray','priceMax','priceMin']));
    }


    public function product($slug){
        echo $slug;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
