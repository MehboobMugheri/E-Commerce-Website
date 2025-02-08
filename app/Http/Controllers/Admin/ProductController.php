<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::latest('id');
        if ($request->get('keyword') != "") {
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }
        $products = $products->paginate(10);
        return view('Admin.product.list',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('Admin.product.create',compact(['categories','brands']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title'     => 'required',
            'slug'      => 'required|unique:products',
            'price'     => 'required|numeric',
            'sku'       => 'required|unique:products',
            'track_qty' => 'required',
            'category'  => 'required',
            'is_featured'=> 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            
            $products = new Product();
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->status = $request->status;
            $products->category_id = $request->category;
            $products->subCategory_id = $request->sub_category;
            $products->brand_id = $request->brand;
            $products->is_featured = $request->is_featured;
            $products->save();

            $request->session()->flash('success','Product added successfully');

            return response()->json([
                'status'    => true,
                'message'   =>'Product added succsesfully',
            ]);

        }else {
            return response()->json([
                'status'    => false,
                'errors'    => $validator->errors(),
            ]);
        }
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
        $products = Product::find($id);
        if (empty($products)) {
            return redirect()->route('products.index')->with('error','Product not found');
        }
        $categories = Category::orderBy('name','ASC')->get();
        $subCategories = SubCategory::where('category_id',$products->category_id)->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('Admin.product.update',compact(['products','categories','brands','subCategories']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $products = Product::find($id);

        $rules = [
            'title'     => 'required',
            // 'slug'      => 'required|unique:products',
            'slug'      => 'required|unique:products,slug,'.$products->id.'id',
            'price'     => 'required|numeric',
            'sku'       => 'required|unique:products,slug,'.$products->id.'id',
            'track_qty' => 'required',
            'category'  => 'required',
            'is_featured'=> 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            
            // $products = new Product();
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->status = $request->status;
            $products->category_id = $request->category;
            $products->subCategory_id = $request->sub_category;
            $products->brand_id = $request->brand;
            $products->is_featured = $request->is_featured;
            $products->save();

            $request->session()->flash('success','Product updated successfully');

            return response()->json([
                'status'    => true,
                'message'   =>'Product updated succsesfully',
            ]);

        }else {
            return response()->json([
                'status'    => false,
                'errors'    => $validator->errors(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = Product::find($id);
        if (empty($products)) {
            return session()->flash('error','Product not found...');

            return response()->json([
                'status'    => false,
                'errors'    => $products->errors(),
            ]);
        }

        $products->delete();
        return  response()->json([
            'status'    => true,
            'message'   => 'Product deleted successfully',
        ]);
        return session()->flash('success','Product not deleted');
    }
}
