<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use App\Http\Requests\StorePreferenceRequest;
use App\Http\Requests\UpdatePreferenceRequest;
use App\Http\Resources\PreferenceResource;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PreferenceResource::collection(Preference::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePreferenceRequest $request)
    {
        $preference = Preference::create($request->all());
        return new PreferenceResource($preference);
    }

    /**
     * Display the specified resource.
     */
    public function show(Preference $preference)
    {
        return new PreferenceResource($preference);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePreferenceRequest $request, Preference $preference)
    {
        $preference->update($request->all());
        return new PreferenceResource($preference);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preference $preference)
    {
        $preference->delete();
        return response(null, 204);
    }
}
