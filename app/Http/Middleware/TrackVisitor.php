<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Jangan track admin & asset
        if (!$request->is('admin*') && !$request->is('*.css') && !$request->is('*.js')) {

            $sessionKey = 'visited_' . md5($request->ip() . $request->path());

            // Hanya catat jika belum pernah visit di session ini
            if (!$request->session()->has($sessionKey)) {

                $agent = $request->userAgent() ?? '';

                // Detect browser
                $browser = 'Unknown';
                if (str_contains($agent, 'Edg'))        $browser = 'Edge';
                elseif (str_contains($agent, 'Chrome')) $browser = 'Chrome';
                elseif (str_contains($agent, 'Firefox'))$browser = 'Firefox';
                elseif (str_contains($agent, 'Safari')) $browser = 'Safari';

                // Detect OS
                $os = 'Unknown';
                if (str_contains($agent, 'Windows'))     $os = 'Windows';
                elseif (str_contains($agent, 'Android')) $os = 'Android';
                elseif (str_contains($agent, 'iPhone'))  $os = 'iOS';
                elseif (str_contains($agent, 'Mac'))     $os = 'MacOS';
                elseif (str_contains($agent, 'Linux'))   $os = 'Linux';

                Visitor::create([
                    'ip_address' => $request->ip(),
                    'browser'    => $browser,
                    'os'         => $os,
                    'page'       => $request->path(),
                ]);

                // Tandai sudah dikunjungi selama session berlangsung
                $request->session()->put($sessionKey, true);
            }
        }

        return $next($request);
    }
}