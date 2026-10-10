<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteSetting;

class CheckModuleStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $settingKey = 'module_' . $module . '_enabled';
        $isEnabled = SiteSetting::get($settingKey, '1');

        if ($isEnabled !== '1' && $isEnabled !== 1 && $isEnabled !== true) {
            // Module is disabled
            $moduleNames = [
                'public' => [
                    'th' => 'คอร์สปฏิบัติธรรมสำหรับประชาชน',
                    'en' => 'Public Meditation Courses',
                    'icon' => 'heart-handshake'
                ],
                'ug' => [
                    'th' => 'ปฏิบัติธรรมระดับปริญญาตรี (40 วัน)',
                    'en' => 'Undergraduate Meditation Module',
                    'icon' => 'graduation-cap'
                ],
                'grad' => [
                    'th' => 'ระบบหนังสือรับรอง บัณฑิตศึกษา (30/45 วัน)',
                    'en' => 'Graduate Studies Certificate Module',
                    'icon' => 'scroll-text'
                ],
                'donation' => [
                    'th' => 'ระบบรับบริจาคเพื่อการปฏิบัติธรรม',
                    'en' => 'Donation & Meditation Fund Module',
                    'icon' => 'gift'
                ],
                'student_portal' => [
                    'th' => 'พอร์ทัลนิสิต มจร',
                    'en' => 'Student Portal',
                    'icon' => 'user-check'
                ],
                'news' => [
                    'th' => 'ข่าวสารและประชาสัมพันธ์',
                    'en' => 'News & Announcements',
                    'icon' => 'newspaper'
                ],
            ];

            $info = $moduleNames[$module] ?? [
                'th' => 'ระบบงานนี้',
                'en' => 'This Module',
                'icon' => 'sparkles'
            ];

            return response()->view('errors.module_inactive', [
                'module' => $module,
                'info' => $info,
            ], 503);
        }

        return $next($request);
    }
}
