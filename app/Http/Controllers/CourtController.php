<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::latest()->get();
        return view('courts.index', compact('courts'));
    }

    public function create()
    {
        return view('courts.create');
    }

    public function store(Request $request)
    {
        Court::create($request->validate($this->rules()));
        return redirect()->route('courts.index')->with('ok', 'Đã thêm sân');
    }

    public function edit(Court $court)
    {
        return view('courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court)
    {
        $court->update($request->validate($this->rules($court)));
        return redirect()->route('courts.index')->with('ok', 'Đã cập nhật sân');
    }

    public function destroy(Court $court)
    {
        $court->delete();
        return redirect()->route('courts.index')->with('ok', 'Đã xóa sân');
    }

    private function rules(?Court $court = null): array
    {
        return [
            'name' => ['required', 'max:100', Rule::unique('courts', 'name')->ignore($court)],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'in:active,maintenance'],
        ];
    }
}
