<?php

use App\Models\Account;
use App\Models\Bank;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\Setting;
use App\Models\UserHasRole;
use App\Models\VoucherSetting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('setting')) {
  function setting()
  {
    return $setting = Setting::firstOrFail();
  }
  function voucher()
  {
    return VoucherSetting::findOrFail(1);
  }
  function accounts()
  {
    return Account::get();
  }
  function banks()
  {
    return Bank::get();
  }
  function userHasPermission($permission)
  {


    $user = Auth::user()->id;
    $role = UserHasRole::where('user_id', $user)->first();

    $permission = Permission::where('name', $permission)->first();
    if ($permission && $role) {
      $valide = RoleHasPermission::where(['role_id' => $role->role_id, 'permission_id' => $permission->id])->first();
      if ($valide) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}
