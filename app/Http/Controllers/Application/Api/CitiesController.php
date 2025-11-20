<?php

namespace App\Http\Controllers\Application\Api;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationCity;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $countryId = $request->get('country_id');
        if (!$countryId) {
            return response()->json([]);
        }

        $cities = ApplicationCity::where('country_id', $countryId)
            ->with('translations')
            ->get()
            ->map(fn($c) => ['id' => $c->id, 'name' => $c->name]);

        return response()->json($cities);
    }
}


