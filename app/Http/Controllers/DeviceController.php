<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function registerDevice(Request $request)
    {
        Device::create(
            [
                'device_token' => $request->input('device_token')
            ]
        );

        return response(['message' => 'created']);
    }
}
