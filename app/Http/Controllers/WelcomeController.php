<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Detection\MobileDetect;
use App\Models\DeviceDetection;
use ipinfo\ipinfo\IPinfo;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('device_detected')) {
            $this->logDeviceDetection($request);
            $request->session()->put('device_detected', true);
        }

        return view('welcome');
    }

    /**
     * Log device detection information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function logDeviceDetection(Request $request)
    {
        $detect = new MobileDetect;
        $ip = $request->ip();
        $ipInfo = (new IPinfo())->getDetails($ip);

        $device = $detect->isMobile() ? 'Mobile' : ($detect->isTablet() ? 'Tablet' : 'Desktop');
        $userAgent = $detect->getUserAgent();
        $platform = $this->getPlatform($userAgent);
        $browser = $this->getBrowser($userAgent);

        DeviceDetection::create([
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'ip' => $ip,
            'ip_info' => json_encode($ipInfo),
        ]);
    }

    /**
     * Get the platform from the user agent string.
     *
     * @param  string  $userAgent
     * @return string
     */
    private function getPlatform($userAgent)
    {
        if (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'Mac';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Get the browser from the user agent string.
     *
     * @param  string  $userAgent
     * @return string
     */
    private function getBrowser($userAgent)
    {
        if (preg_match('/chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/msie|trident/i', $userAgent)) {
            return 'Internet Explorer';
        } elseif (preg_match('/edge/i', $userAgent)) {
            return 'Edge';
        } else {
            return 'Unknown';
        }
    }
}
