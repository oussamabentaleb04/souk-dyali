<?php

namespace App\Http\Controllers;

use App\Models\SellerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerApplicationController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        // Already applied or already a seller — show status instead of the form
        if ($user->sellerProfile) {
            return view('seller.application-status', ['profile' => $user->sellerProfile]);
        }

        return view('seller.apply');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->sellerProfile) {
            return back()->with('error', 'You already have a seller application.');
        }

        $data = $request->validate([
            'shop_name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'region' => ['required', 'string', 'max:100'],
        ]);

        $base = Str::slug($data['shop_name']) ?: 'shop';
        $slug = $base;
        $i = 2;
        while (SellerProfile::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        SellerProfile::create([
            'user_id' => $user->id,
            'shop_name' => $data['shop_name'],
            'slug' => $slug,
            'description' => $data['description'],
            'region' => $data['region'],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.apply')->with('success', 'Your application has been submitted. We will review it shortly.');
    }
}