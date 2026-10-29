<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Contact::latest();
        if ($filter === 'unread') {
            $query->where('is_read', false);
        }
        $messages = $query->paginate(10)->withQueryString();

        $unread = Contact::where('is_read', false)->count();

        return view('admin.messages.index', compact('messages', 'filter', 'unread'));
    }

    public function markRead(Contact $message)
    {
        $message->update(['is_read' => !$message->is_read]);

        return back()->with('success', $message->is_read ? 'Đã đánh dấu đã xử lý.' : 'Đã đánh dấu chưa xử lý.');
    }

    public function destroy(Contact $message)
    {
        $message->delete();

        return back()->with('success', 'Đã xóa liên hệ.');
    }
}
