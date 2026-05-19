<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        $query = Scheme::active();

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($searchQuery) use ($request) {
                $searchQuery->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        $schemes    = $query->latest()->paginate(9);
        $categories = Scheme::CATEGORIES;

        return view('schemes.index', compact('schemes', 'categories'));
    }

    public function show(Scheme $scheme)
    {
        return view('schemes.show', compact('scheme'));
    }

    public function apply(Request $request, Scheme $scheme)
    {
        // For now, redirect to external apply link or show success
        if ($scheme->apply_link) {
            return redirect($scheme->apply_link);
        }
        return back()->with('success', 'Application for "' . $scheme->title . '" submitted! You will be contacted shortly.');
    }
}
