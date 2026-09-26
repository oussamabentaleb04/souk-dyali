<?php

namespace App\Http\Controllers;

class AdminReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', [
            'reviews' => \App\Models\Review::with(['user', 'product'])->latest()->paginate(20),
        ]);
    }

    public function toggle(\App\Models\Review $review)
    {
        $review->update(['is_visible' => ! $review->is_visible]);

        return back()->with('success', $review->is_visible ? 'Review shown.' : 'Review hidden.');
    }

    public function destroy(\App\Models\Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}