<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::latest('id');
        // return $brands;
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name','like','%'.$request->get('keyword').'%');
    }

    $brands = $brands->paginate(10);
       return view('Admin.brands.list',compact('brands')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required|unique:brands',
            'status'    => 'required',
        ]);

        if ($validator->passes()) {
            $brands = new Brand();
            $brands->name   = $request->name;
            $brands->slug   = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success','Brands created succesfully..!');

        return response([
            'status'    => true,
            'message'   => 'Brands created succesfully..!',
        ]);
            
        }else {
            
            return response()->json([
                'status'    => false,
                'error'  => $validator->errors(),
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
        $brands = Brand::find($id);
        
        if (empty($brands)) {
            return redirect()->route('brands.index');
            $request->session()->flash('error','Brand not found..!');

            return response()->json([
                'status'    => false,
                'error'     => $brands->error(),
            ]);
        }

        return view('Admin.brands.update',compact('brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brands = Brand::find($id);

        if (empty($brands)) {
            // return redirect()->route('brands.index');

            $request->session()->flash('error','Brand not found..!');

            return response()->json([
                'status'    => false,
                'notFound'  => true,
            ]);    
        }

        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'slug'      => 'required|unique:brands,slug,'.$brands->id.'id',
            'status'    => 'required',
        ]);

        if ($validator->passes()) {
            $brands->name   = $request->name;
            $brands->slug   = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success','Brands updated succesfully..!');

        return response([
            'status'    => true,
            'message'   => 'Brands updated succesfully..!',
        ]);
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
        $brands = Brand::find($id);

        if (empty($brands)) {
            $request->session()->flash('error','Brand not found.!');

            return response([
                'status'  => false,
                'notFound'=> true
            ]);
            
        }
            $brands->delete();

            $request->session()->flash('success','Brand deleted succesfully..!');

            return response([
                'status' => true,
                'message'=> 'Brand deleted succesfully' 
            ]);
    }
}
