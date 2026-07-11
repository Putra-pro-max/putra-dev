<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Wajib di-import agar View::composer aktif
use App\Models\ContactMessage;       // Sesuai dengan tabel contact_messages Anda
use App\Models\Visitor;              // Sesuai dengan tabel visitors Anda

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menyediakan data otomatis ke layout admin di halaman mana pun
        View::composer('admin.layout', function ($view) {
            try {
                // 1. Ambil jumlah pesan yang belum dibaca (is_read = false / 0)
                $unreadCount = ContactMessage::where('is_read', false)->count();
                
                // 2. Ambil data total pengunjung global (untuk baris 232 di layout Anda)
                $totalVisitorsCount = Visitor::count();
            } catch (\Exception $e) {
                // Pencegahan agar jika database bermasalah, aplikasi tidak langsung blank hitam
                $unreadCount = 0;
                $totalVisitorsCount = 0;
            }

            // Kirim kedua variabel secara resmi ke view admin.layout
            $view->with([
                'unreadMessages' => $unreadCount,
                'totalVisitors' => $totalVisitorsCount
            ]);
        });
    }
}