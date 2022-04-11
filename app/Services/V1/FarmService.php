<?php

namespace App\Services\V1;


use Illuminate\Support\Facades\Storage;

class FarmService
{

    public function __construct()
    {
    }

    /**
     * Create farm files
     */
    public function manageFarmFiles($request, $farm = null)
    {
        $images = [];

        if($farm != null){
            if ($farm->images && count($farm->images)) {
                foreach($farm->images as $image) {
                    $keep = false;

                    if (count($request->old_images)) {
                        foreach($request->old_images as $oldImage) {
                            if (data_get($oldImage, 'path') === data_get($image, 'path')) {
                                $keep = true;

                                $images[] = [
                                    'name' => data_get($image, 'name'),
                                    'path' => data_get($image, 'path'),
                                ];

                                break;
                            }
                        }
                    }

                    if (!$keep) {
                        // remove old image
                        Storage::disk('public')->delete(data_get($image, 'path'));
                    }
                }
            }
        }

        if($request->hasFile('images')){
            $files = $request->file('images');

            foreach($files as $file) {
                $fileName = $file->getClientOriginalName();
                $path = $file->storePublicly('farms/', ['disk' => 'public']);

                $images[] = [
                    'name' => $fileName,
                    'path' => '/'. $path,
                ];

                if (count($images) > 5) {
                    // remove first image
                    Storage::disk('public')->delete(data_get($images, '0.path'));
                    array_shift($images);
                }
            }
        }
        return $images;
    }

}
