<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Resources\CattleLocation\CattleLocationListResource;
use App\Models\CattleLocation;

use App\Services\V1\CattleLocationService;
use App\Services\V1\CommonService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CattleLocation\CreateCattleLocationRequest;

class CattleLocationController extends Controller
{
    protected CattleLocationService $cattleLocationService;
    protected CommonService $commonService;

    public function __construct()
    {
        $this->cattleLocationService = new CattleLocationService;
        $this->commonService = new CommonService;
    }

    /**
     * fetch all employees
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        [$page, $itemsPerPage, $orderBy, $orderDir] = $this->commonService->getPaginationHeader();

        $sortColumns = [
            'id', 'cattles.name', 'longitude', 'latitude'
        ];

        if (!in_array($orderBy, $sortColumns)) {
            $orderBy = 'cattle_locations.created_at';
            $orderDir = 'desc';
        }

        $user = Auth::user();

        $cattle = CattleLocation::with('cattle')->orderBy($orderBy, $orderDir);
        if (!$user->is_super_admin) {
            $cattle = $cattle->where('added_by', $user->id);
        }
        $cattle = $cattle->filter(request()->only('search', 'trashed'));

        $cattle = $cattle->skip(($page - 1) * $itemsPerPage)->paginate($itemsPerPage);

        if(empty($itemsPerPage) || $itemsPerPage < 1){
            $itemsPerPage = 1;
        }

        return response()->json([
            'data' => CattleLocationListResource::collection($cattle),
            'meta' => [
                'total' => $cattle->total(),
                'last_page' => ceil($cattle->total() / intval($itemsPerPage)),
            ]
        ], 200);
    }

    public function show($id)
    {
        $cattle = CattleLocation::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'cattle' => new CattleLocationListResource($cattle)
            ]
        ]);
    }

    public function store(CreateCattleLocationRequest $request)
    {
        $auth_user = Auth::user();
        $green_zone = $purple_zone = $orange_zone = $red_zone = null;

        //Finding zones on the basis of the latitude and longitude


        // create cattle
        $cattleLocation = CattleLocation::create([

            'cattle_id' => $request->cattle_id,

            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'green_zone' => $green_zone,
            'purple_zone' => $purple_zone,
            'orange_zone' => $orange_zone,
            'red_zone' => $red_zone,
            'grazing' => $request->grazing,
            'standing' => $request->standing,
            'sitting' => $request->sitting,

            'location_date' => $request->location_date,
            'added_by' => $auth_user->id,
            'updated_by' => $auth_user->id,
        ]);


        return response()->json([
            'success' => true,
            'message' => __('form.cattle_location_lang.created'),
            'data' => [
                'cattle_location_id' => $cattleLocation->id,
            ]
        ]);
    }

    public function testCoordinate(){
        $cattle_obj = new CattleLocationService();
        $cattle_obj->locateCoordinates(1, -33.871028, 151.020579);
    }

}
