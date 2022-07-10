<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\FarmFence\CreateFarmFenceCoordsRequest;
use App\Http\Requests\FarmFence\CreateFarmFenceRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceCoordsRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceRequest;
use App\Http\Resources\FarmFence\FarmFenceResource;
use App\Http\Resources\FarmFence\FarmFenceListResource;


use App\Models\FarmFence;
use App\Models\FarmFenceCoordinates;
use App\Services\V1\FarmFenceService;
use Illuminate\Support\Facades\Auth;

class FarmFenceController extends Controller
{
    protected FarmFenceService $farmFenceService;

    public function __construct()
    {
        $this->farmFenceService = new FarmFenceService;
    }

    /**
     * fetch all employees
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $page = (int) \request('page') ?? 1;
        $itemsPerPage = (int) \request('itemsPerPage') ?? 10;
        $orderBy = (\request('sortBy'));
        $orderDir = (\request('sortDesc'));

        if (!in_array($orderDir, ['asc', 'desc'])) {
            $orderDir = 'ASC';
        }

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

    public function coordinates($id){
        $farmFence = FarmFence::withTrashed()->find($id);
        return $this->farmFenceService->getCoOrdinates($farmFence, request()->all());
    }

    public function storeCoords(CreateFarmFenceCoordsRequest $request){
        $auth_user = Auth::user();
        $farmFence = FarmFence::withTrashed()->find($request->farm_fence_id);


        $farmFence->update(['center_lat'=>data_get($request->center, 'lat'), 'center_lng'=>data_get($request->center, 'lng')]);
        // sync fence co-ordinate
        $places = [];
        if (count($request->places)) {
            $farmFence->coordinates()->forceDelete();
            foreach($request->places as $place) {
                if(data_get($place, 'lng', '') != '' && data_get($place, 'lat', '') != ''){
                    $places[] = [
                        'farm_fence_id' => $farmFence->id,
                        'longitude' => data_get($place, 'lng'),
                        'latitude' => data_get($place, 'lat'),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'added_by' => $auth_user->id,
                        'updated_by' => $auth_user->id,
                    ];
                }
            }
            FarmFenceCoordinates::insert($places);
        }

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.created'),
            'data' => [
                'farm_fence_coords_id' => $farmFence->id,
            ]
        ]);
    }

    public function updateCoords(UpdateFarmFenceCoordsRequest $request, $id)
    {
        $farmFenceCoords = FarmFenceCoordinates::withTrashed()->find($id);

        $auth_user = Auth::user();

        $farmFenceCoords->update([
            'farm_fence_id' => $request->farm_fence_id,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,

            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.updated'),
            'data' => [
                'farm_fence_coords' => $farmFenceCoords->id,
            ]
        ]);
    }

    public function destroyCoords($id)
    {
        $farmFence = FarmFenceCoordinates::withTrashed()->find($id);
        $farmFence->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.deleted'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

    public function restoreCoords($id)
    {
        $farmFence = FarmFenceCoordinates::withTrashed()->find($id);

        $farmFence->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_coords_lang.restored'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

}
