<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImageUpload extends Controller
{
    public function create(Request $request){
        // if ($request->hasFile('image')) {
        //     dd($request->file('image'));
        // }else {
        //     dd('not');
        // }
        
        $image = $request->image;
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();

            $newName = time(). '.'.$ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            //Generate Thumbnail
            $manager = new ImageManager(new driver());

            $source_path = public_path().'/temp/'.$newName;
            $dest_path   = public_path().'/temp/thumb/'.$newName;
            
            $image = $manager->read($source_path);
            $image->cover(300,275);
            $image->save($dest_path);

            // $image->move(public_path().'/temp',$newName);

            return response()->json([
                'status'    => true,
                'image_id'  => $tempImage->id,
                'message'   => 'Image uploaded successfully',
            ]);

        }
    
    }
}
