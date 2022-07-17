<?php

namespace App\Services\V1;


use App\Models\Cattle;
use Illuminate\Support\Facades\Storage;
use Location\Coordinate;
use Location\Polygon;

class CattleLocationService
{

    public function __construct()
    {
    }

    public function locateCoordinates($cattle_id, $lat, $lng){

        //find cattle farm
        $cattle = Cattle::find($cattle_id);
        $fences = $cattle->farm->fences;
        //need to check with each farm fence for all coordinates
        foreach ($fences as $fence){
            $coordinates = $fence->coordinates;
            //create polygon object in order to find if the current co-ordinate is inside or outside the polygon of the fence
            $geofence = new Polygon();
            foreach ($coordinates as $coordinate){
                $geofence->addPoint(new Coordinate($coordinate->latitude,$coordinate->longitude));
            }

            $outsidePoint = new Coordinate($lat, $lng);
            dd($geofence->contains($outsidePoint));
        }

    }

}
