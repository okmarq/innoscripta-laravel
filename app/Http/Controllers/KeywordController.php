<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Http\Requests\StoreKeywordRequest;
use App\Http\Requests\UpdateKeywordRequest;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Keyword::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKeywordRequest $request)
    {
        return Keyword::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Keyword $keyword)
    {
        return $keyword;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeywordRequest $request, Keyword $keyword)
    {

        return $keyword->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
        return response(null, 204);
    }
}
