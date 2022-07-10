<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\FarmStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farms\CreateFarmRequest;
use App\Http\Requests\Farms\UpdateFarmRequest;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use App\Services\V1\CommonService;
use App\Services\V1\FarmGoogleService;
use App\Services\V1\FarmService;
use Illuminate\Support\Facades\Auth;

class FarmsController extends Controller
{
    protected FarmService $farmService;
    protected CommonService $commonService;

    public function __construct()
    {
        $this->farmService = new FarmService;
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
            'id', 'name', 'phone', 'status'
        ];

        if (!in_array($orderBy, $sortColumns)) {
            $orderBy = 'farms.name';
        }

        $user = Auth::user();

        $farms = Farm::orderBy($orderBy, $orderDir);
        if (!$user->is_super_admin) {
            $farms = $farms->where('added_by', $user->id);
        }
        $farms = $farms->filter(request()->only('search', 'trashed'));

        $farms = $farms->skip(($page - 1) * $itemsPerPage)->paginate($itemsPerPage);

        if(empty($itemsPerPage) || $itemsPerPage < 1){
            $itemsPerPage = 1;
        }

        return response()->json([
            'data' => FarmResource::collection($farms),
            'meta' => [
                'total' => $farms->total(),
                'last_page' => ceil($farms->total() / intval($itemsPerPage)),
            ]
        ], 200);
    }

    public function show($id)
    {
        $farm = Farm::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'farm' => new FarmResource($farm)
            ]
        ]);
    }

    public function store(CreateFarmRequest $request)
    {
        $auth_user = Auth::user();

        $images = (new CommonService())->manageMultiFiles($request,'farms');

        // create Farm
        $farm = Farm::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'api_key' => $request->api_key,
            'notes' => $request->notes,
            'status' => $request->status,
            'images' => $images,

            'added_by' => $auth_user->id,
            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_lang.created'),
            'data' => [
                'farm_id' => $farm->id,
            ]
        ]);
    }

    public function update(UpdateFarmRequest $request, $id)
    {
        $farm = Farm::withTrashed()->find($id);

        $auth_user = Auth::user();
        $images = (new CommonService())->manageMultiFiles($request,'farms', $farm);

        $farm->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'api_key' => $request->api_key,
            'notes' => $request->notes,
            'status' => $request->status,
            'images' => $images,

            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_lang.updated'),
            'data' => [
                'farm' => new FarmResource($farm),
            ]
        ]);
    }

    public function destroy($id)
    {
        $farm = Farm::withTrashed()->find($id);

        $farm->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_lang.deleted'),
            'data' => [
                'farm' => new FarmResource($farm),
            ]
        ]);
    }

    public function restore($id)
    {
        $farm = Farm::withTrashed()->find($id);

        $farm->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_lang.restored'),
            'data' => [
                'farm' => new FarmResource($farm),
            ]
        ]);
    }

    /**
     * Get all Farms Status List
     */
    public function getStatusList()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => FarmStatus::ALL
            ],
        ]);
    }

    public function list(){
        $user = Auth::user();
        $farms = Farm::select('id', 'name');
        if (!$user->is_super_admin) {
            $farms = $farms->where('added_by', $user->id);
        }
        $farms = $farms->get()->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'datas' => $farms,
            ],
        ]);
    }

    public function linkGoogle($id){
        $farm = Farm::find($id);
        if($farm){
            $address = $farm->address;
            $farmGoogleService = new FarmGoogleService();
            $lat_lng = $farmGoogleService->getMapLocationUsingAddress($farm->api_key, $address);
            if($lat_lng && !empty($lat_lng['lat']) && !empty($lat_lng['lat']) ){
                $farm->update($lat_lng);
                return response()->json([
                    'success' => true,
                    'message' => __('form.farm_lang.map_linked'),
                    'data' => [
                        'farm' => null,
                    ],
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => __('form.farm_lang.link_failed'),
            'data' => [
                'farm' => null,
            ],
        ]);
    }

}
