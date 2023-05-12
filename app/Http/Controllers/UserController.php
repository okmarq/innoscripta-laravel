<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserResource;
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

    /**
     * Display the specified resource.
     */
    public function saveSourcePreference(string $source)
    {
        // return new ArticleResource($source);
        // 'source',
    }

    /**
     * Display the specified resource.
     */
    public function saveCategoryPreference(string $category)
    {
        // return new ArticleResource($category);
        // 'category',
    }

    /**
     * Display the specified resource.
     */
    public function saveAuthorPreference(string $author)
    {
        // return new ArticleResource($author);
        // 'author',
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
