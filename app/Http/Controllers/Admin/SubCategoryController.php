<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
                         ->latest('sub_categories.id')
                         ->leftJoin('categories','categories.id','sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
                $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
                $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }

        $subCategories = $subCategories->paginate(10);

        // dd($request->all());
        // dd($request->input('category_id')); // Check specific input
    // dd($request->query'category_id')); // If it's coming from GET request

        return view('Admin.Sub-Category.list',compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::OrderBy('name','ASC')->get();
        return view('Admin.Sub-Category.sub-category',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required',
            // 'category_id'  => 'required',
            // 'status'    => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory = new  SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            // return $subCategory->category_id;
            // dd($request->all());
            // dd($request->input('category_id')); // Check specific input
            // return($request->query('category_id'));

            $request->session()->flash('success','Sub Category inserted seccesfully');

            return response([
                'status' => true,
                'message'=> 'Sub Category inserted seccesfully' 
            ]);

            // $validator->name   -> $request->name,
        }else {
            return response([
                'status' => false,
                'error'  => $validator->errors()
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
    public function edit(string $id , Request $request)
    {
        $subCategories = SubCategory::find($id);

        if (empty($subCategories)) {
            $request->session()->flash('error','Sub Category not found.!');
            return redirect()->route('sub-category.index');
        }

        $categories = Category::orderBy('name','ASC')->get();
        return view('Admin.Sub-Category.update',compact(['categories','subCategories']));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subCategories = SubCategory::find($id);

        if (empty($subCategories)) {
            $request->session()->flash('error','Sub Category not updated.!');
            // return redirect()->route('sub-category.index');
            return response([
                'status'  => false,
                'notFound'=> true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required',
            // 'category_id'  => 'required',
            // 'status'    => 'required',
        ]);

        if ($validator->passes()) {

            // $subCategory = new  SubCategory();
            $subCategories->name       = $request->name;
            $subCategories->slug       = $request->slug;
            $subCategories->status     = $request->status;
            $subCategories->showHome = $request->showHome;
            $subCategories->category_id = $request->category;
            $subCategories->save();

            $request->session()->flash('success','Sub Category Updated seccesfully');

            return response([
                'status' => true,
                'message'=> 'Sub Category Updated seccesfully' 
            ]);
            // $validator->name   -> $request->name,
        }else {
            return response([
                'status' => false,
                'error'  => $validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id , Request $request)
    {
        $subCategories = SubCategory::find($id);

        // // return $subCategories;
        // $subCategories->delete();
        // return redirect()->route('sub-category.index');

        if (empty($subCategories)) {
            $request->session()->flash('error','Sub Category not found.!');
            
            return response([
                'status'  => false,
                'notFound'=> true
            ]);
            
        }
            $subCategories->delete();

            $request->session()->flash('success','Sub Category deleted seccesfully..!');

            return response([
                'status' => true,
                'message'=> 'Sub Category deleted seccesfully' 
            ]);
    }
}
