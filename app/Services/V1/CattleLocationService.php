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

    public function locateCoordinates(Cattle $cattle, $lat, $lng){

        //find

        //create polygon object in order to find if the current co-ordinate is inside or outside the polygon of the fence
        $geofence = new Polygon();

        $geofence->addPoint(new Coordinate(-12.085870,-77.016261));
        $geofence->addPoint(new Coordinate(-12.086373,-77.033813));
        $geofence->addPoint(new Coordinate(-12.102823,-77.030938));
        $geofence->addPoint(new Coordinate(-12.098669,-77.006476));

        $outsidePoint = new Coordinate(-12.075452, -76.985079);
        $insidePoint = new Coordinate(-12.092542, -77.021540);

    }

}
