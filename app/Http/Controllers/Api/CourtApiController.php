<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtApiController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance',
        ];
    }

    public function index()
    {
        return Court::orderBy('id')->get();
    }

    public function store(Request $request)
    {
        $court = Court::create($request->validate($this->rules()));
        return response()->json($court, 201);
    }

    public function show(Court $court)
    {
        return $court;
    }

    public function update(Request $request, Court $court)
    {
        $court->update($request->validate($this->rules()));
        return $court;
    }

    public function destroy(Court $court)
    {
        $court->delete();
        return response()->noContent();
    }
}