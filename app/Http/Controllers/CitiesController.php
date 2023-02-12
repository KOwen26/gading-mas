<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    //

    public function index(Cities $cities)
    {
        //
        return ['cities' => $cities->orderBy('city_name')->get()];
    }

    public function search(Request $request, Cities $cities)
    {
        $cities = $cities::where('city_name', 'LIKE', "%{$request->search}%")->get();
        return response()->json($cities);
    }

    public function show($id, Cities $cities)
    {
        $cities = $cities::find($id);
        return response()->json($cities);
    }
}