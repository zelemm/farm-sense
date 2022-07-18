<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\FarmFence\CreateFarmFenceCoordsRequest;
use App\Http\Requests\FarmFence\CreateFarmFenceRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceCoordsColorRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceCoordsRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceRequest;
use App\Http\Resources\FarmFence\FarmFenceCoordinatesResource;
use App\Http\Resources\FarmFence\FarmFenceResource;
use App\Http\Resources\FarmFence\FarmFenceListResource;


use App\Models\FarmFence;
use App\Models\FarmFenceCoordinates;
use App\Services\V1\CommonService;
use App\Services\V1\FarmFenceService;
use Illuminate\Support\Facades\Auth;

class FarmFenceController extends Controller
{
    protected FarmFenceService $farmFenceService;
    protected CommonService $commonService;

    public function __construct()
    {
        $this->farmFenceService = new FarmFenceService;
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
            'id', 'label', 'farm_name'
        ];

        if (!in_array($orderBy, $sortColumns)) {
            $orderBy = 'farm_name';
        }

        $user = Auth::user();


        switch ($orderBy) {
            case 'farm_name':
                $farmFence = FarmFence::select('farm_fences.*', 'farms.name as farm_name')
                    ->leftJoin('farms', 'farms.id', '=', 'farm_fences.farm_id');
                break;
            default:
                $farmFence = FarmFence::select('farm_fences.*')->orderBy($orderBy, $orderDir);
                break;
        }

        if (!$user->is_super_admin) {
            $farmFence = $farmFence->where('added_by', $user->id);
        }
        $farmFence = $farmFence->filter(request()->only('search', 'trashed'));

        $farmFence = $farmFence->orderBy($orderBy, $orderDir);
        $farmFence = $farmFence->skip(($page - 1) * $itemsPerPage)->paginate($itemsPerPage);

        if(empty($itemsPerPage) || $itemsPerPage < 1){
            $itemsPerPage = 1;
        }

        return response()->json([
            'data' => FarmFenceListResource::collection($farmFence),
            'meta' => [
                'total' => $farmFence->total(),
                'last_page' => ceil($farmFence->total() / intval($itemsPerPage)),
            ]
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $farmGoogle = FarmFence::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'farm_fence' => new FarmFenceResource($farmGoogle)
            ]
        ]);
    }

    /**
     * @param CreateFarmFenceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateFarmFenceRequest $request)
    {
        $auth_user = Auth::user();

        // create cattle
        $farmFence = FarmFence::create([

            'farm_id' => $request->farm_id,
            'label' => $request->label,
            'description' => $request->description,

            'added_by' => $auth_user->id,
            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_lang.created'),
            'data' => [
                'farm_fence_id' => $farmFence->id,
            ]
        ]);
    }

    /**
     * @param UpdateFarmFenceRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateFarmFenceRequest $request, $id)
    {
        $farmFence = FarmFence::withTrashed()->find($id);

        $auth_user = Auth::user();

        $farmFence->update([
            'farm_id' => $request->farm_id,
            'label' => $request->label,
            'description' => $request->description,

            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_lang.updated'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $farmFence = FarmFence::withTrashed()->find($id);
        $farmFence->coordinates()->delete();
        $farmFence->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_lang.deleted'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $farmFence = FarmFence::withTrashed()->find($id);

        $farmFence->restore();
        $farmFence->coordinates()->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_lang.restored'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function coordinates($id){
        $farmFence = FarmFence::withTrashed()->find($id);
        return $this->farmFenceService->getCoOrdinates($farmFence, request()->all());
    }

    /**
     * @param $fencePlaces
     * @param $center
     * @return array
     */
    public function formatPlacesWithCenter($fencePlaces, $center){
        $places = [];
        foreach($fencePlaces as $place) {
            if(data_get($place, 'lng', '') != '' && data_get($place, 'lat', '') != ''){
                $places[] = [
                    'lng' => data_get($place, 'lng'),
                    'lat' => data_get($place, 'lat'),
                ];
            }
        }
        $center_point = ['lat'=>data_get($center, 'lat'), 'lng'=>data_get($center, 'lng')];
        return [$center_point, $places];
    }

    public function storeCoords(CreateFarmFenceCoordsRequest $request){
        $auth_user = Auth::user();
        $farmFence = FarmFence::withTrashed()->find($request->farm_fence_id);

        // sync fence co-ordinate
        if (count($request->places)) {
            [$center_point, $places] = $this->formatPlacesWithCenter($request->places, $request->center);
            $fenceCoords = [
                'farm_fence_id' => $farmFence->id,
                'center_point' => $center_point,
                'fence_coordinates' => $places,
                'added_by' => $auth_user->id,
                'updated_by' => $auth_user->id,
            ];
            FarmFenceCoordinates::create($fenceCoords);
        }

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.created'),
            'data' => [
                'farm_fence_coords_id' => $farmFence->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCoords($id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'farm_fence' => new FarmFenceCoordinatesResource($farmFenceCoords)
            ]
        ]);
    }

    /**
     * @param UpdateFarmFenceCoordsRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoords(UpdateFarmFenceCoordsRequest $request, $id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->find($id);

        $auth_user = Auth::user();

        // sync fence co-ordinate
        if (count($request->places)) {
            [$center_point, $places] = $this->formatPlacesWithCenter($request->places, $request->center);
            $fenceCoords = [
                'center_point' => $center_point,
                'fence_coordinates' => $places,
                'updated_by' => $auth_user->id,
            ];
            $farmFenceCoords->update($fenceCoords);
        }

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.updated'),
            'data' => [
                'farm_fence_coords' => $farmFenceCoords->id,
            ]
        ]);
    }

    /**
     * @param UpdateFarmFenceCoordsRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoordsColor(UpdateFarmFenceCoordsColorRequest $request, $id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->find($id);

        $auth_user = Auth::user();

        // sync fence co-ordinate
        if ($request->fence_color) {
            $fenceCoords = [
                'fence_color' => $request->fence_color,
                'updated_by' => $auth_user->id,
            ];
            $farmFenceCoords->update($fenceCoords);
        }

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.color_updated'),
            'data' => [
                'farm_fence_coords' => $farmFenceCoords->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCoords($id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->find($id);
        $farmFenceCoords->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.deleted'),
            'data' => [
                'farm_fence' => $farmFenceCoords->id,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreCoords($id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->find($id);

        $farmFenceCoords->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.restored'),
            'data' => [
                'farm_fence' => $farmFenceCoords->id,
            ]
        ]);
    }

}
