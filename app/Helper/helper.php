<?php

use App\Models\Setting;

if (!function_exists('setting')) {
  function setting()
  {
    return $setting = Setting::firstOrFail();
  }
}
