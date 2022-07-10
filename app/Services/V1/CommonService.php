<?php

namespace App\Services\V1;


use Illuminate\Support\Facades\Storage;

class CommonService
{

    public function __construct()
    {
    }

    public function getPaginationHeader(){
        $page = (int) \request('page') ?? 1;
        $itemsPerPage = (int) \request('itemsPerPage') ?? 10;
        $orderBy = (\request('sortBy'));
        $orderDir = (\request('sortDesc'));

        if (!in_array($orderDir, ['asc', 'desc'])) {
            $orderDir = 'ASC';
        }
        return [$page, $itemsPerPage, $orderBy, $orderDir];
    }

    /**
     * Create farm files
     */
    public function manageMultiFiles($request, $type = '', $obj = null)
    {
        $images = [];

        if($obj != null){
            if ($obj->images && count($obj->images)) {
                foreach($obj->images as $image) {
                    $keep = false;

                    if ($request->old_images && count($request->old_images)) {
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
                $path = $file->storePublicly($type.'/', ['disk' => 'public']);

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

    public function getImageLink($images){
        return array_map(function($image) {
            $pathUrl = '';
            if (data_get($image, 'path')) {
                if (Storage::disk('public')->exists(data_get($image, 'path'))) {
                    $pathUrl = Storage::disk('public')->url(data_get($image, 'path'));
                }
            }

            return [
                'name' => data_get($image, 'name'),
                'path' => data_get($image, 'path'),
                'path_url' => $pathUrl,
            ];
        }, $images);
    }

}
