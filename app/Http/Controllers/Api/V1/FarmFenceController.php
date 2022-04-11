<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\FarmFence\CreateFarmFenceRequest;
use App\Http\Requests\FarmFence\UpdateFarmFenceRequest;
use App\Http\Resources\FarmFence\FarmFenceResource;
use App\Http\Resources\FarmFence\FarmFenceListResource;


use App\Models\FarmFence;
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
            'id', 'longitude', 'latitude', 'farm_name'
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
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,

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
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,

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

        return response()->json([
            'success' => true,
            'message' => __('form.farm_fence_lang.restored'),
            'data' => [
                'farm_fence' => $farmFence->id,
            ]
        ]);
    }

}
