<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreferenceRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserResource;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function savePreference(StorePreferenceRequest $request)
    {
        $preference = new Preference($request->all());

        $user = auth()->user();

        $user->preferences()->save($preference);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(null, 204);
    }
}
