<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::all();

        return $this->success(SettingResource::collection($settings));
    }

    public function update(UpdateSettingRequest $request): JsonResponse
    {
        foreach ($request->validated('settings') as $item) {
            Setting::updateOrCreate(
                ['key'   => $item['key']],
                ['value' => $item['value']]
            );
        }

        return $this->success(
            SettingResource::collection(Setting::all()),
            'Settings updated successfully'
        );
    }
}
