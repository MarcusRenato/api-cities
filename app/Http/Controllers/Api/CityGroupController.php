<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityGroupResource;
use App\Models\CityGroup;
use Illuminate\Http\Request;

class CityGroupController extends Controller
{
    /**
     * The CityGroup instance
     *
     * @var CityGroup
     */
    protected $cityGroup;

    /**
     * The new controller instance
     *
     * @param CityGroup $cityGroup
     * @return void
     */
    public function __construct(CityGroup $cityGroup)
    {
        $this->cityGroup = $cityGroup;
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->cityGroup->all();

        return response()->json(CityGroupResource::collection($groups));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validator($request);

        try {
            $cityGroup = $this->cityGroup->create($data);

            return response()->json(
                new CityGroupResource($cityGroup),
                201
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cityGroup = $this->cityGroup->findOrFail($id);

        return response()->json([
            new CityGroupResource($cityGroup)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validator($request);

        $cityGroup = $this->cityGroup->findOrFail($id);

        try {
            $cityGroup->update($data);

            return response()->json([
                'data' => new CityGroupResource($cityGroup)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cityGroup = $this->cityGroup->findOrFail($id);

        try {

            $cityGroup->delete();

            return response()->json([], 204);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Valida dados para inserção
     *
     * @param Request $request
     * @return array
     */
    private function validator(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|min:3|max:255'
        ]);
    }
     
}
