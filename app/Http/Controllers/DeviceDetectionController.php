<?php

namespace App\Http\Controllers;

use App\Models\DeviceDetection;
use Illuminate\Http\Request;

class DeviceDetectionController extends Controller
{
    public function index()
    {
        $detections = DeviceDetection::all();
        return view('admin.device_detections.index', compact('detections'));
    }
}
