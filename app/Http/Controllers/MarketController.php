<?php

namespace App\Http\Controllers;

use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketPrice::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }
        if ($request->filled('trend')) {
            $query->where('trend', $request->trend);
        }

        $prices     = $query->latest('price_date')->paginate(15);
        $categories = \App\Models\Crop::CATEGORIES;
        $states     = MarketPrice::distinct()->pluck('state')->sort()->values();

        return view('market.index', compact('prices', 'categories', 'states'));
    }

    public function show(MarketPrice $marketPrice)
    {
        // Get price history for chart (last 30 days for same crop/market)
        $priceHistory = MarketPrice::where('crop_name', $marketPrice->crop_name)
            ->where('market_name', $marketPrice->market_name)
            ->latest('price_date')
            ->take(30)
            ->get()
            ->reverse()
            ->values();

        return view('market.show', compact('marketPrice', 'priceHistory'));
    }

    public function search(Request $request)
    {
        $results = MarketPrice::search($request->q)->latest('price_date')->take(20)->get();
        return response()->json($results);
    }
}
