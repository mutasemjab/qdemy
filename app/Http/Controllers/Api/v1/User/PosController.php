<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BankQuestion;
use App\Models\MinisterialYearsQuestion;
use App\Models\POS;
use App\Traits\Responses;

class PosController extends Controller
{
    use Responses;

    public function index()
    {
        $pos = POS::get()
            ->groupBy('country_name')
            ->map(function ($items, $countryName) {
                return [
                    'country_name' => $countryName,
                    'items' => $items->values()
                ];
            })
            ->values();
        
        return $this->success_response(__('pos fetched successfully'), $pos);
    }
    
}
