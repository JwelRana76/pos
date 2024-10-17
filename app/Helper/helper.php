<?php

use App\Models\Account;
use App\Models\Bank;
use App\Models\Setting;

if (!function_exists('setting')) {
  function setting()
  {
    return $setting = Setting::firstOrFail();
  }
  function accounts()
  {
    return Account::get();
  }
  function banks()
  {
    return Bank::get();
  }
}
