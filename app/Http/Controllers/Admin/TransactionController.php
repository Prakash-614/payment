<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Website;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('website')->latest();

        if ($request->filled('website_id')) {
            $query->where('website_id', $request->website_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // High-level statistics
        $totalVolume = Transaction::where('status', 'completed')->sum('amount');
        $successfulCount = Transaction::where('status', 'completed')->count();
        $totalCount = Transaction::count();
        $successRate = $totalCount > 0 ? round(($successfulCount / $totalCount) * 100, 1) : 0;

        $websites = Website::orderBy('name')->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalVolume',
            'successfulCount',
            'totalCount',
            'successRate',
            'websites'
        ));
    }
}