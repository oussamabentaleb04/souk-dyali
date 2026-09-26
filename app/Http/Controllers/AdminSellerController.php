<?php

namespace App\Http\Controllers;

use App\Models\SellerProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSellerController extends Controller
{
    public function index(Request $request)
    {
        $query = SellerProfile::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.sellers.index', [
            'sellers' => $query->get(),
            'currentStatus' => $request->input('status', ''),
        ]);
    }

    public function updateStatus(Request $request, SellerProfile $sellerProfile)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected', 'pending'])],
        ]);

        $sellerProfile->update($data);

        // Keep the user's role in sync: approved sellers get the "seller" role,
        // rejected/pending applicants stay (or return to) "buyer".
        $sellerProfile->user->update([
            'role' => $data['status'] === 'approved' ? 'seller' : 'buyer',
        ]);

        return back()->with('success', $sellerProfile->shop_name . ' is now ' . $data['status'] . '.');
    }
}