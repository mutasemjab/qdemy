<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doseyat extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'price' => 'double',
    ];

    // Relationships
    public function pos()
    {
        return $this->belongsTo(POS::class, 'pos_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

      public function cards()
    {
        return $this->belongsToMany(Card::class, 'card_doseyat_frees', 'doseyat_id', 'card_id')
            ->withTimestamps();
    }
}
