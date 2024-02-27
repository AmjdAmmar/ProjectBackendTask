<?php
// app/Traits/UploadsImages.php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Images;

trait UploadsImages
{
    /**
     * Upload image for the model.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    // private function uploadImage1(Request $request, $model)
    // {
    //     if ($request->hasFile('image1')) {
    //         $image1 = $request->file('image1');
    //         $imageName1 = time() . '.' . $image1->getClientOriginalExtension();
    //         $image1->move(public_path('public/images/'), $imageName1);
    //         $model->image1 = $imageName1;
    //     }
    // }

    // private function uploadImage2(Request $request, $model)
    // {
    //     if ($request->hasFile('image2')) {
    //         $image2 = $request->file('image2');
    //         $imageName2 = time() . '.' . $image2->getClientOriginalExtension();
    //         $image2->move(public_path('images'), $imageName2);
    //         $model->image2 = $imageName2;
    //     }
    // }



    // private function uploadImage($image1, $image2, $product)
    // {
    //     $path1 = $image1->store('public/images/');
    //     if ($image2 === null) {
    //         $path2 = null;
    //     } else {
    //         $path2 = $image2->store('public/images/');
    //     }

    //     // // Create a new image record and associate it with the product
    //     $mergedImage = new Images([
    //         'imageable_type' => Product::class,
    //         'imageable_id' => $product->id,
    //         'image1' =>  $path1, // Provide the path to image1 here
    //         'image2' =>    $path2, // Provide the path to image2 here
    //     ]);
    //     // Save the merged image
    //     $mergedImage->save();
    //     return  $mergedImage;
    // }
    private function uploadImages($images, $product)
{
    $imagePaths = [];

    foreach ($images as $index => $image) {
        $path = $image->move(public_path('images'), time() . "_$index." . $image->getClientOriginalExtension());
        $imagePaths[] = $path->getRealPath();
    }

    // Create a new image record for each image and associate it with the product
    foreach ($imagePaths as $imagePath) {
        $mergedImage = new Images([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'image' =>  $imagePath, // Provide the path to the image here
        ]);

        $mergedImage->save();
    }

    return $imagePaths;
}



}
