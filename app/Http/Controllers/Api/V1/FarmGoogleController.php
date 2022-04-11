<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\FarmGoogleStatus;
use App\Http\Controllers\Controller;

use App\Http\Requests\FarmGoogle\CreateFarmGoogleRequest;
use App\Http\Requests\FarmGoogle\UpdateFarmGoogleRequest;
use App\Http\Resources\FarmGoogle\FarmGoogleResource;
use App\Http\Resources\FarmGoogle\FarmGoogleListResource;



use App\Models\FarmGoogle;
use App\Services\V1\FarmGoogleService;
use Illuminate\Support\Facades\Auth;

class FarmGoogleController extends Controller
{
    protected FarmGoogleService $farmGoogleService;

    public function __construct()
    {
        $this->farmGoogleService = new FarmGoogleService;
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
            'id', 'label', 'client_id', 'organisation_id'
        ];

        if (!in_array($orderBy, $sortColumns)) {
            $orderBy = 'farm_googles.label';
        }

        $user = Auth::user();

        $farmGoogle = FarmGoogle::with('farm')->orderBy($orderBy, $orderDir);
        if (!$user->is_super_admin) {
            $farmGoogle = $farmGoogle->where('added_by', $user->id);
        }
        $farmGoogle = $farmGoogle->filter(request()->only('search', 'trashed'));

        $farmGoogle = $farmGoogle->skip(($page - 1) * $itemsPerPage)->paginate($itemsPerPage);

        if(empty($itemsPerPage) || $itemsPerPage < 1){
            $itemsPerPage = 1;
        }

        return response()->json([
            'data' => FarmGoogleListResource::collection($farmGoogle),
            'meta' => [
                'total' => $farmGoogle->total(),
                'last_page' => ceil($farmGoogle->total() / intval($itemsPerPage)),
            ]
        ], 200);
    }

    public function show($id)
    {
        $farmGoogle = FarmGoogle::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'farm_google' => new FarmGoogleResource($farmGoogle)
            ]
        ]);
    }

    public function store(CreateFarmGoogleRequest $request)
    {
        $auth_user = Auth::user();

        // create cattle
        $farmGoogle = FarmGoogle::create([

            'farm_id' => $request->farm_id,
            'label' => $request->label,
            'organisation_id' => $request->organisation_id,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,

            'scope' => $request->scope,
            'timezone' => $request->timezone,
            'status' => $request->status,

            'added_by' => $auth_user->id,
            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_google_lang.created'),
            'data' => [
                'farm_google_id' => $farmGoogle->id,
            ]
        ]);
    }

    public function update(UpdateFarmGoogleRequest $request, $id)
    {
        $farmGoogle = FarmGoogle::withTrashed()->find($id);

        $auth_user = Auth::user();

        $farmGoogle->update([


            'farm_id' => $request->farm_id,
            'label' => $request->label,
            'organisation_id' => $request->organisation_id,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,

            'scope' => $request->scope,
            'timezone' => $request->timezone,
            'status' => $request->status,

            'updated_by' => $auth_user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('form.farm_google_lang.updated'),
            'data' => [
                'farm_google' => new FarmGoogleResource($farmGoogle),
            ]
        ]);
    }

    public function destroy($id)
    {
        $farmGoogle = FarmGoogle::withTrashed()->find($id);

        $farmGoogle->delete();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_google_lang.deleted'),
            'data' => [
                'farm_google' => new FarmGoogleListResource($farmGoogle),
            ]
        ]);
    }

    public function restore($id)
    {
        $farmGoogle = FarmGoogle::withTrashed()->find($id);

        $farmGoogle->restore();

        return response()->json([
            'success' => true,
            'message' => __('form.farm_google_lang.restored'),
            'data' => [
                'farm_google' => new FarmGoogleListResource($farmGoogle),
            ]
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
                'farmGoogleStatus' => FarmGoogleStatus::ALL
            ],
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function google_redirect(){
        $all_params = request()->all();
        $return = [
            'success' => false,
            'message' => __('form.farm_google_lang.google_auth_error'),
            'data' => [
                'farmGoogleStatus' => FarmGoogleStatus::ALL
            ],
        ];
        if(isset($all_params['code'])){
            $code = $all_params['code'];
            $organization_ar = explode(':', base64_decode($all_params["state"]))??[];
            if($organization_ar[0] && $organization_ar[1]){
                $client = $this->auth($organization_ar[1], $code);
                if($client->getRefreshToken() != null || $client->getRefreshToken() != ""){
                    return redirect('http://127.0.0.1:8000/super_admin/farm_google')->with($return);
                    return response()->json([
                        'success' => true,
                        'message' => __('form.farm_google_lang.google_auth_success'),
                        'data' => [
                            'farmGoogleStatus' => FarmGoogleStatus::ALL
                        ],
                    ]);
                }
            }
        }
        $return['success'] = false;
        $return['message'] = __('form.farm_google_lang.google_auth_error');
        return redirect('http://127.0.0.1:8000/super_admin/farm_google')->with($return);
        return response()->json([
            'success' => false,
            'message' => __('form.farm_google_lang.google_auth_error'),
            'data' => [
                'farmGoogleStatus' => FarmGoogleStatus::ALL
            ],
        ]);
    }


    public function refreshToken(){
        $farm_googles = FarmGoogle::where("status", FarmGoogleStatus::ACTIVE)->get();
        foreach ($farm_googles as $farm_google){
            $this->auth($farm_google->id, null, 1);
        }
    }//end of function

    public function auth($id, $code=null, $renew = 0)
    {
        $farmGoogle = FarmGoogle::find($id);
        if(!$farmGoogle || $farmGoogle->status == FarmGoogleStatus::INACTIVE){
            return response()->json([
                'success' => false,
                'message' => __('form.farm_google_lang.not_found'),
                'data' => [
                    'farm_google' => [],
                ]
            ]);
        }

        $tokens = ["access_token"=>"", "token_type"=>"", "scope"=>"", "expires_in"=>""];
        $data_to_update = [];

        $data_to_update["google_scopes"] = explode(" ", $farmGoogle->scope);

        $client = $this->farmGoogleService->createGoogleObject($farmGoogle);

        //check if the data exists in the DB
        if(empty($code) && $renew == 0){
            $googleAuthUrl = $this->farmGoogleService->getAuthURL($client, $farmGoogle, $id);
            return response()->json([
                'success' => true,
                'message' => __('form.farm_google_lang.auth_started'),
                'data' => [
                    'auth_redirect' => $googleAuthUrl,
                ]
            ]);
        }
        else if($code != null){
            return $this->farmGoogleService->fetchTokenFromCode($client, $farmGoogle, $code, $data_to_update);
        }
        if ($code == null && $client->isAccessTokenExpired()) {
            $this->farmGoogleService->fetchTokenFromRefreshToken($client, $farmGoogle, $data_to_update);
            return response()->json([
                'success' => true,
                'message' => __('form.farm_google_lang.token_renewed'),
                'data' => [
                ]
            ]);
        }
        elseif ($code == null){
            return response()->json([
                'success' => true,
                'message' => __('form.farm_google_lang.auth_existing'),
                'data' => [
                ]
            ]);
        }

    }

}
