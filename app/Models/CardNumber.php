<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardNumber extends Model
{
    use HasFactory;
  protected $guarded = [];


    protected $casts = [
        'activate' => 'integer',
        'status'   => 'integer',
    ];

    // Constants for activate field
    const ACTIVATE_ACTIVE = 1;
    const ACTIVATE_INACTIVE = 2;

    // Constants for status field
    const STATUS_USED = 1;
    const STATUS_NOT_USED = 2;

    /**
     * Get the card that owns the card number
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get activate status text
     */
    public function getActivateTextAttribute()
    {
        return $this->attributes['activate'] == self::ACTIVATE_ACTIVE ? __('messages.active') : __('messages.inactive');
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->attributes['status'] == self::STATUS_USED ? __('messages.used') : __('messages.not_used');
    }

     public function getFormattedNumberAttribute()
    {
        return chunk_split($this->attributes['number'], 4, '-');
    }
}
