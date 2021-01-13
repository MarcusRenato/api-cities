<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * The city instance
     *
     * @var City
     */
    protected $city;

    /**
     * The new controller instance
     *
     * @param City $city
     * @return void
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = $this->city->all();

        return response()->json(CityResource::collection($cities));
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
            $city = $this->city->create($data);

            return response()->json(
                new CityResource($city),
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
        $city = $this->city->findOrFail($id);

        return response()->json([
            new CityResource($city)
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

        try {
            $city = $this->city->findOrFail($id);

            $city->update($data);

            return response()->json([
                'data' => new CityResource($city)
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
        $city = $this->city->findOrFail($id);

        try {

            $city->delete();

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
        $data = [];

        if (request()->method() === 'PUT') {
            $data = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'city_group_id' => 'nullable|integer'
            ]);
        } elseif (request()->method() === 'POST') {
            $data = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'city_group_id' => 'required|integer'
            ]);
        }

        return $data;
    }
}
