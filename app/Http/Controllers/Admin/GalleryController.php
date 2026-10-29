<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = Artwork::with('owner')->latest();
        if ($status) {
            $query->where('status', $status);
        }
        $artworks = $query->paginate(12)->withQueryString();

        return view('admin.gallery.index', compact('artworks', 'status'));
    }

    public function review(Request $request, Artwork $artwork)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,rejected,revision',
            'review_note' => 'nullable|string|max:1000',
        ]);

        $artwork->update([
            'status' => $data['status'],
            'review_note' => $data['review_note'] ?? null,
            'reviewer_id' => auth()->id(),
        ]);

        $labels = [
            'approved' => 'Đã duyệt tác phẩm.',
            'rejected' => 'Đã từ chối tác phẩm.',
            'revision' => 'Đã yêu cầu chỉnh sửa.',
        ];

        return back()->with('success', $labels[$data['status']]);
    }
}
