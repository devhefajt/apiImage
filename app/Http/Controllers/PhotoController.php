<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('photos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('photos.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    // public function store(StoreImageRequest $request)
    // {

    //     $validatedData = $request->validated();

    //     $images = [];

    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $image) {
    //             if ($image->isValid()) {

    //                 $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    //                 $image->move(public_path('images'), $imageName);
    //                 $images[] = 'images/' . $imageName;
    //             } else {
    //                 return response()->json(['error' => 'Invalid image file'], 422);
    //             }
    //         }
    //     }

    //     $validatedData['images'] = $images;
    //     $photo = Photo::create($validatedData);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Photo uploaded successfully!',
    //         'data' => $photo
    //     ], 201);
    // }

    // public function store(StoreImageRequest $request)
    // {
    //     $validatedData = $request->validated();

    //     $images = [];

    //     // Decode and save images
    //     foreach ($validatedData['images'] as $imageData) {
    //         $base64Image = $imageData['base64'];
    //         $imageName = time() . '_' . uniqid() . '.png';

    //         $imagePath = public_path('images/' . $imageName);

    //         // Decode and save the image
    //         $decodedImage = base64_decode(explode(',', $base64Image)[1]);
    //         file_put_contents($imagePath, $decodedImage);

    //         $images[] = 'images/' . $imageName; // Save path
    //     }

    //     $validatedData['images'] = $images;
    //     $photo = Photo::create($validatedData);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Photo uploaded successfully!',
    //         'data' => $photo
    //     ], 201);

    // }




    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // return $id;
        $photo = Photo::findOrFail($id);
        return view('photos.edit', compact('photo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, $id)
    {
        $validatedData = $request->validated();

        // Retrieve the photo record by ID
        $photo = Photo::findOrFail($id);

        // Array to hold the final list of images
        $images = $photo->images ?? [];

        // Handle existing images
        if ($request->has('existing_images')) {
            $existingImages = $request->input('existing_images');

            // Find images to remove
            $imagesToRemove = array_diff($images, $existingImages);

            foreach ($imagesToRemove as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image)); // Delete from the filesystem
                }
            }

            // Retain only the images that are still marked as existing
            $images = $existingImages;
        } else {
            // If no existing images are provided, remove all old images
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image)); // Delete from the filesystem
                }
            }
            $images = []; // Reset to an empty array
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageName);
                    $images[] = 'images/' . $imageName; // Add new image path to array
                } else {
                    return response()->json(['error' => 'Invalid image file'], 422);
                }
            }
        }

        // Update the photo record
        $photo->update(['images' => $images] + $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Photo updated successfully!',
            'data' => $photo
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
