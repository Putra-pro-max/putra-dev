<?php
namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\ContactMessage;
use App\Models\Project;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalVisitors'   => Visitor::count(),
            'todayVisitors'   => Visitor::whereDate('created_at', today())->count(),
            'uniqueVisitors'  => Visitor::distinct('ip_address')->count(),
            'totalMessages'   => ContactMessage::count(),
            'unreadMessages'  => ContactMessage::where('is_read', false)->count(),
            'totalProjects'   => Project::count(),
            'recentVisitors'  => Visitor::latest()->take(8)->get(),
            'recentMessages'  => ContactMessage::latest()->take(5)->get(),
            'browserStats'    => Visitor::selectRaw('browser, count(*) as total')->groupBy('browser')->get(),
            'osStats'         => Visitor::selectRaw('os, count(*) as total')->groupBy('os')->get(),
            'dailyVisitors'   => Visitor::selectRaw('DATE(created_at) as date, count(*) as total')
                                    ->groupBy('date')->orderBy('date')->take(7)->get(),
        ];

        return view('admin.dashboard', $data);
    }

    public function messages()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages', compact('messages'));
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function destroyMessage(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Pesan dihapus.');
    }
    
}