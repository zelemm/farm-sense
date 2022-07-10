<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\CattleStatus;
use App\Enums\CattleArrivalStatus;
use App\Enums\CattleBreed;
use App\Enums\CattleSex;
use App\Enums\CattleType;
use App\Http\Controllers\Controller;

use App\Http\Requests\Cattle\CreateCattleRequest;
use App\Http\Requests\Cattle\UpdateCattleRequest;
use App\Http\Resources\Cattle\CattleResource;
use App\Http\Resources\Cattle\CattleListResource;

use App\Models\Cattle;

use App\Services\V1\CattleService;
use App\Services\V1\CommonService;
use Illuminate\Support\Facades\Auth;

class CattleController extends Controller
{
    protected CattleService $cattleService;
    protected CommonService $commonService;

    public function __construct()
    {
        $this->cattleService = new CattleService;
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
            $orderBy = 'cattles.name';
        }

        $user = Auth::user();

        $cattle = Cattle::with('farm')->orderBy($orderBy, $orderDir);
        if (!$user->is_super_admin) {
            $cattle = $cattle->where('added_by', $user->id);
        }
        $cattle = $cattle->filter(request()->only('search', 'trashed'));

        $cattle = $cattle->skip(($page - 1) * $itemsPerPage)->paginate($itemsPerPage);

        if(empty($itemsPerPage) || $itemsPerPage < 1){
            $itemsPerPage = 1;
        }

        return response()->json([
            'data' => CattleListResource::collection($cattle),
            'meta' => [
                'total' => $cattle->total(),
                'last_page' => ceil($cattle->total() / intval($itemsPerPage)),
            ]
        ], 200);
    }

    public function show($id)
    {
        $cattle = Cattle::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'cattle' => new CattleResource($cattle)
            ]
        ]);
    }

    public function store(CreateCattleRequest $request)
    {
        $auth_user = Auth::user();

        $images = (new CommonService())->manageMultiFiles($request,'cattles');

        // create cattle
        $cattle = Cattle::create([

            'farm_id' => $request->farm_id,
            'mac_id' => $request->mac_id,
            'cattle_type' => $request->cattle_type,

            'images' => $images,

            'name' => $request->name,
            'arrival' => $request->arrival,
            'date_of_birth' => $request->date_of_birth,
            'date_of_death' => $request->date_of_death,
            'date_of_sell' => $request->date_of_sell,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'casterated' => $request->casterated,
            'vendor' => $request->vendor,
            'purchase_price' => $request->purchase_price,
            'sold_price' => $request->sold_price,

            'mother_cow' => $request->mother_cow,
            'father_cow' => $request->father_cow,

            'notes' => $request->notes,
            'status' => $request->status,

            'added_by' => $auth_user->id,
            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.cattle_lang.created'),
            'data' => [
                'cattle_id' => $cattle->id,
            ]
        ]);
    }

    public function update(UpdateCattleRequest $request, $id)
    {
        $cattle = Cattle::withTrashed()->find($id);

        $auth_user = Auth::user();
        $images = (new CommonService())->manageMultiFiles($request,'cattles', $cattle);

        $cattle->update([

            'farm_id' => $request->farm_id,
            'mac_id' => $request->mac_id,
            'cattle_type' => $request->cattle_type,

            'images' => $images,

            'name' => $request->name,
            'arrival' => $request->arrival,
            'date_of_birth' => $request->date_of_birth,
            'date_of_death' => $request->date_of_death,
            'date_of_sell' => $request->date_of_sell,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'casterated' => $request->casterated,
            'vendor' => $request->vendor,
            'purchase_price' => $request->purchase_price,
            'sold_price' => $request->sold_price,

            'mother_cow' => $request->mother_cow,
            'father_cow' => $request->father_cow,

            'notes' => $request->notes,
            'status' => $request->status,

            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.cattle_lang.updated'),
            'data' => [
                'cattle' => new CattleResource($cattle),
            ]
        ]);
    }

    public function destroy($id)
    {
        $cattle = Cattle::withTrashed()->find($id);

        $cattle->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.cattle_lang.deleted'),
            'data' => [
                'cattle' => new CattleListResource($cattle),
            ]
        ]);
    }

    public function restore($id)
    {
        $cattle = Cattle::withTrashed()->find($id);

        $cattle->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.cattle_lang.restored'),
            'data' => [
                'cattle' => new CattleListResource($cattle),
            ]
        ]);
    }

    /**
     * Get all Cattle Breed List
     */
    public function getBreed()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cattleBreed' => CattleBreed::ALL
            ],
        ]);
    }

    /**
     * Get all Cattle Sex List
     */
    public function getCattleSex()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cattleSex' => CattleSex::ALL
            ],
        ]);
    }

    /**
     * Get all Cattle Type List
     */
    public function getCattleType()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cattleType' => CattleType::ALL
            ],
        ]);
    }

    /**
     * Get all Cattle Status List
     */
    public function getStatusList()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cattleStatus' => CattleStatus::ALL
            ],
        ]);
    }

    /**
     * Get all Cattle Arrival Status List
     */
    public function getCattleArrivalStatusList()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cattleArrivalStatus' => CattleArrivalStatus::ALL
            ],
        ]);
    }

    public function list(){
        $cow_id = request()->cow_id ? explode(',', request()->cow_id):null;
        $user = Auth::user();
        $cattle = Cattle::select('id', 'name');
        if (!$user->is_super_admin) {
            $cattle = $cattle->where('added_by', $user->id);
        }
        if($cow_id && count($cow_id) > 0){
            $cattle = $cattle->whereNotIn('id', [$cow_id]);
        }
        $cattle = $cattle->get()->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'datas' => $cattle,
            ],
        ]);
    }

}
