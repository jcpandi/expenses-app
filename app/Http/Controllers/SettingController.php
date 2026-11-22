<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const CURRENCIES = ['PHP', 'USD', 'EUR', 'GBP', 'JPY', 'SGD', 'AUD', 'CAD', 'HKD', 'KRW', 'CNY', 'INR', 'AED', 'SAR'];

    public function show()
    {
        return ['currency' => Setting::get('currency', 'PHP')];
    }

    public function update(Request $request)
    {
        $data = $request->validate(['currency' => ['required', 'in:'.implode(',', self::CURRENCIES)]]);
        Setting::put('currency', $data['currency']);

        return $data;
    }
}
