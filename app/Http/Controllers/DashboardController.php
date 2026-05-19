<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Order;
use App\Models\MarketPrice;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isBuyer()) {
            return $this->buyer($user);
        }

        if ($user->isAdmin()) {
            return $this->admin($user);
        }

        return $this->farmer($user);
    }

    private function farmer(User $user)
    {
        // Stats
        $totalCrops    = Crop::forUser($user->id)->count();
        $activeCrops   = Crop::forUser($user->id)->active()->count();
        $salesQuery = Order::whereHas('crop', fn ($query) => $query->where('user_id', $user->id));
        $totalEarnings = (float) (string) (clone $salesQuery)->whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount');
        $totalOrders   = (clone $salesQuery)->count();
        $pendingOrders = (clone $salesQuery)->where('status', 'pending')->count();

        // Recent activity
        $recentCrops  = Crop::forUser($user->id)->latest()->take(5)->get();
        $recentOrders = (clone $salesQuery)->with('crop')->latest()->take(5)->get();

        // Market top prices
        $topMarketPrices = MarketPrice::latest('price_date')->take(6)->get();

        // Chart data - Crop distribution by category
        $cropCategories = Crop::forUser($user->id)->get()
            ->groupBy('category')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Chart data - Monthly earnings (last 6 months)
        $monthlyEarnings = Order::whereHas('crop', fn ($query) => $query->where('user_id', $user->id))
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m');
            })
            ->map(function ($group) {
                return [
                    'label' => $group->first()->created_at->format('M Y'),
                    'total' => (float) (string) $group->sum('total_amount'),
                ];
            })
            ->sortKeys()
            ->values();

        return view('dashboard.farmer', compact(
            'user',
            'totalCrops',
            'activeCrops',
            'totalEarnings',
            'totalOrders',
            'pendingOrders',
            'recentCrops',
            'recentOrders',
            'topMarketPrices',
            'cropCategories',
            'monthlyEarnings'
        ));
    }

    private function buyer(User $user)
    {
        $purchaseQuery = Order::where('user_id', $user->id);

        $availableCrops = Crop::active()->count();
        $totalPurchases = (clone $purchaseQuery)->count();
        $pendingPurchases = (clone $purchaseQuery)->where('status', 'pending')->count();
        $totalSpent = (float) (string) (clone $purchaseQuery)->whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount');
        $recentPurchases = (clone $purchaseQuery)->with('crop.user')->latest()->take(6)->get();
        $featuredCrops = Crop::active()->with('user')->latest()->take(6)->get();
        $topMarketPrices = MarketPrice::latest('price_date')->take(6)->get();

        return view('dashboard.buyer', compact(
            'user',
            'availableCrops',
            'totalPurchases',
            'pendingPurchases',
            'totalSpent',
            'recentPurchases',
            'featuredCrops',
            'topMarketPrices'
        ));
    }

    private function admin(User $user)
    {
        $totalUsers = User::count();
        $totalFarmers = User::where('role', User::ROLE_FARMER)->count();
        $totalBuyers = User::where('role', User::ROLE_BUYER)->count();
        $totalCrops = Crop::count();
        $totalOrders = Order::count();
        $totalRevenue = (float) (string) Order::whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount');
        $activeSchemes = Scheme::active()->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $latestUsers = User::latest()->take(6)->get();
        $recentOrders = Order::with('crop.user')->latest()->take(6)->get();

        return view('dashboard.admin', compact(
            'user',
            'totalUsers',
            'totalFarmers',
            'totalBuyers',
            'totalCrops',
            'totalOrders',
            'totalRevenue',
            'activeSchemes',
            'pendingOrders',
            'latestUsers',
            'recentOrders'
        ));
    }

    public function stats()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->isAdmin()) {
            return response()->json([
                'totalUsers' => User::count(),
                'totalFarmers' => User::where('role', User::ROLE_FARMER)->count(),
                'totalBuyers' => User::where('role', User::ROLE_BUYER)->count(),
                'totalCrops' => Crop::count(),
                'totalOrders' => Order::count(),
                'totalRevenue' => (float) (string) Order::whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount'),
                'activeSchemes' => Scheme::active()->count(),
                'pendingOrders' => Order::where('status', 'pending')->count(),
            ]);
        }

        if ($user->isBuyer()) {
            $purchaseQuery = Order::where('user_id', $user->id);
            return response()->json([
                'availableCrops' => Crop::active()->count(),
                'totalPurchases' => (clone $purchaseQuery)->count(),
                'pendingPurchases' => (clone $purchaseQuery)->where('status', 'pending')->count(),
                'totalSpent' => (float) (string) (clone $purchaseQuery)->whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount'),
            ]);
        }

        // Farmer
        $salesQuery = Order::whereHas('crop', fn ($query) => $query->where('user_id', $user->id));
        return response()->json([
            'totalCrops' => Crop::forUser($user->id)->count(),
            'activeCrops' => Crop::forUser($user->id)->active()->count(),
            'totalEarnings' => (float) (string) (clone $salesQuery)->whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total_amount'),
            'totalOrders' => (clone $salesQuery)->count(),
            'pendingOrders' => (clone $salesQuery)->where('status', 'pending')->count(),
        ]);
    }
}
