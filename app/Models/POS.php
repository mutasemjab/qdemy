<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class POS extends Authenticatable
{
    use HasFactory;

    protected $table = 'p_o_s';
    protected $guarded = [];
    protected $hidden = ['password'];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function getTotalCardsAttribute()
    {
        return $this->cards()->count();
    }

    /**
     * Get the total number of card numbers for this POS
     */
    public function getTotalCardNumbersAttribute()
    {
        return $this->cards()->withCount('cardNumbers')->get()->sum('card_numbers_count');
    }

       public static function getGroupedByCountry()
    {
        return self::all()->groupBy('country_name');
    }
}
