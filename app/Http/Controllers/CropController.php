<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CropController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isBuyer()) {
            return redirect()->route('orders.sell');
        }

        $query = $user->isAdmin()
            ? Crop::with('user')
            : Crop::forUser($user->id)->with('user');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $crops      = $query->latest()->paginate(10);
        $categories = Crop::CATEGORIES;
        $statuses   = Crop::STATUSES;

        return view('crops.index', compact('crops', 'categories', 'statuses'));
    }

    public function create()
    {
        $categories = Crop::CATEGORIES;
        $units      = Crop::UNITS;
        return view('crops.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|in:' . implode(',', Crop::CATEGORIES),
            'quantity'       => 'required|numeric|min:0',
            'unit'           => 'required|in:' . implode(',', Crop::UNITS),
            'price_per_unit' => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'harvest_date'   => 'nullable|date',
            'location'       => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'status'         => 'required|in:' . implode(',', Crop::STATUSES),
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('crops', 'public');
        }

        $validated['user_id'] = Auth::id();
        $crop = Crop::create($validated);

        return redirect()->route('crops.index')
            ->with('success', "Crop '{$crop->name}' added successfully!");
    }

    public function show(Crop $crop)
    {
        $this->authorize('view', $crop);
        return view('crops.show', compact('crop'));
    }

    public function edit(Crop $crop)
    {
        $this->authorize('update', $crop);
        $categories = Crop::CATEGORIES;
        $units      = Crop::UNITS;
        $statuses   = Crop::STATUSES;
        return view('crops.edit', compact('crop', 'categories', 'units', 'statuses'));
    }

    public function update(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|in:' . implode(',', Crop::CATEGORIES),
            'quantity'       => 'required|numeric|min:0',
            'unit'           => 'required|in:' . implode(',', Crop::UNITS),
            'price_per_unit' => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'harvest_date'   => 'nullable|date',
            'location'       => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'status'         => 'required|in:' . implode(',', Crop::STATUSES),
        ]);

        if ($request->hasFile('image')) {
            if ($crop->image) {
                Storage::disk('public')->delete($crop->image);
            }
            $validated['image'] = $request->file('image')->store('crops', 'public');
        }

        $crop->update($validated);

        return redirect()->route('crops.index')
            ->with('success', "Crop '{$crop->name}' updated successfully!");
    }

    public function destroy(Crop $crop)
    {
        $this->authorize('delete', $crop);

        if ($crop->image) {
            Storage::disk('public')->delete($crop->image);
        }
        $name = $crop->name;
        $crop->delete();

        return redirect()->route('crops.index')
            ->with('success', "Crop '{$name}' deleted successfully!");
    }

    public function toggleStatus(Crop $crop)
    {
        $this->authorize('update', $crop);
        $crop->update(['status' => $crop->status === 'active' ? 'pending' : 'active']);
        return back()->with('success', 'Crop status updated!');
    }
}
