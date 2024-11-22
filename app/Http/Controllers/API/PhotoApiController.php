<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use App\Http\Requests\StoreImageRequest;

class PhotoApiController extends Controller
{
    public function index()
    {
        $items = Photo::paginate(7);
        return response()->json($items);
    }



    public function store(StoreImageRequest $request)
    {
        $validatedData = $request->validated();

        $images = [];

        // Decode and save images
        foreach ($validatedData['images'] as $imageData) {
            $base64Image = $imageData['base64'];
            $imageName = time() . '_' . uniqid() . '.png';

            $imagePath = public_path('images/' . $imageName);

            // Decode and save the image
            $decodedImage = base64_decode(explode(',', $base64Image)[1]);
            file_put_contents($imagePath, $decodedImage);

            $images[] = 'images/' . $imageName; // Save path
        }

        $validatedData['images'] = $images;
        $photo = Photo::create($validatedData);
        return response()->json([
            'message' => 'Photo uploaded successfully!',
            'data' => $photo
        ], 201);

    }



    public function destroy($id)
    {
        // Find the photo by ID
        $photo = Photo::find($id);

        // Check if the photo exists
        if (!$photo) {
            return response()->json([
                'message' => 'Photo not found.',
            ], 404);
        }

        // Delete associated images from the public directory
        if (is_array($photo->images)) {
            foreach ($photo->images as $imagePath) {
                $fullPath = public_path($imagePath); // Construct the full file path
                if (file_exists($fullPath)) {
                    unlink($fullPath); // Delete the file
                }
            }
        }

        // Delete the entire record from the database
        $photo->delete();

        return response()->json([
            'message' => 'Photo and all associated data deleted successfully!',
        ], 200);
    }
}
