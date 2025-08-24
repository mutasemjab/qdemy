<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpinionStudent extends Model
{
    use HasFactory;

    protected $guarded = [];

      protected $casts = [
        'number_of_star' => 'decimal:1',
    ];


    /**
     * Get the star rating as an array for display.
     *
     * @return array
     */
    public function getStarRatingAttribute()
    {
        $rating = $this->attributes['number_of_star'];
        $stars = [];
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars[] = 'full';
            } elseif ($i - 0.5 <= $rating) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }
        
        return $stars;
    }

}
