<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
                $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);

        return view('category.list',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required',
        ]);

        if ($validate->passes()) {
            $category = Category::create([
                'name'    => $request->name,
                'slug'    => $request->slug,
            ]);

            // Save image Here

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);

                $ext = last($extArray);

                $newImageName = $category->id . '.' .$ext;
                $source_path = public_path().'/temp/'.$tempImage->name;
                $dest_path   = public_path().'/uploads/category/'.$newImageName;
                File::copy($source_path,$dest_path);

                //Generate Image Thumbnail
                $dest_path = public_path().'/uploads/category/thumbs/'.$newImageName;
                $manager = new ImageManager(new driver());

                $image = $manager->read($source_path);
                $image->cover(540,600);
                $image->save($dest_path);

                $category->image = $newImageName;
                $category->save();
            } 

            $request->session()->flash('success','Category inserted succesfully');

            return response()->json([
                'status'    => true,
                'message'   => 'Category inserted succesfully',
            ]);

            return redirect()->route('category.index')->with('success','Category added successfully');

        }else {
            return response()->json([
                'status'    => false,
                'errors'    => $validate->errors()
            ]);
        }

        return view('category.index',compact('category'));
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
        $category = Category::find($id);

        if ($category->id != $id) {
            return redirect()->route('category.index');
        }
        $user = auth()->user(); 
        if (Gate::allows('id',[$user , $category]) == $id) {
            return redirect()->route('category.index');
        }

        
        return view('category.update',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if ($category->id != $id) {
            return response()->json([
                'status'  => false,
                'notFound'=> true,
                'message' => 'Category not found',
            ]);
            }

        $update = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required|unique:categories,'.$category->categoryId.'id',
        ]);

        if ($update->passes()) {
            // $update = Category::findOrFail($id)
            // ->update([
            //     'name'  => $request->name,
            //     'slug'  => $request->slug,
            //     'status'  => $request->status,
            //     // 'showHome'  => $request->showHome,
            // ]);
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);

                $ext = last($extArray);

                $newImageName = $category->id . '.' .$ext;
                $source_path = public_path().'/temp/'.$tempImage->name;
                $dest_path   = public_path().'/uploads/category/'.$newImageName;
                File::copy($source_path,$dest_path);

                //Generate Image Thumbnail
                $dest_path = public_path().'/uploads/category/thumbs/'.$newImageName;
                $manager = new ImageManager(new driver());

                $image = $manager->read($source_path);
                $image->cover(540,600);
                $image->save($dest_path);

                $category->image = $newImageName;
                $category->save();
            } 
            
            // return $update;
            
            $request->session()->flash('success','Category updated succesfully');
            
            return response()->json([
                'status'    => true,
                'message'   => 'Category updated succesfully',
            ]);
            
            // return redirect()->route('category.index');
            
        }else {
            return response()->json([
                'status'    => false,
                'errors'    => $update->errors()
            ]);
        }
        
        // return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $category = Category::find($id);
        if (empty($category) ) {
            // return redirect()->route('category.index');
            $request->session()->flash('error','Category not found');
            return response()->json([
                'status' => true,
                'message'=> 'Category not found',
            ]);
        }

        $category->delete();

        $request->session()->flash('success','Category delete succesfully');

        return response()->json([
            'status' => true,
            'message'=> 'Category deleted successfully',
        ]);
    }
}
