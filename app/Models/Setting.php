<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = [];

     public function getLogoUrlAttribute()
    {
       return $this->logo ? asset('assets/admin/uploads/' . $this->logo) : asset('assets_front/images/logo-white.png') ;
    }

     public static function first()
    {
        return static::query()->first();
    }

    /**
     * Get settings as a singleton
     */
    public static function getSettings()
    {
        static $settings = null;
        
        if ($settings === null) {
            $settings = static::first();
        }
        
        return $settings;
    }


}
