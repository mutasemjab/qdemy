<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\Responses;

class SettingController extends Controller
{
   use Responses;

    public function index()
    {
        $data = Setting::first();

        return $this->success_response('Settings Retrived Successfully',$data);
    }


}
