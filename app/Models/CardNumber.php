<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

     public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    
    public function cardUsages()
    {
        return $this->hasMany(CardUsage::class);
    }

    public function latestUsage()
    {
        return $this->hasOne(CardUsage::class)->latest();
    }

    // Check if card number is available for assignment
    public function isAvailable()
    {
        return $this->assigned_user_id === null && 
               $this->status === self::STATUS_NOT_USED && 
               $this->activate === self::ACTIVATE_ACTIVE;
    }

    // Check if card number is assigned but not used
    public function isAssignedButNotUsed()
    {
        return $this->assigned_user_id !== null && 
               $this->status === self::STATUS_NOT_USED;
    }

    // Check if card number is used
    public function isUsed()
    {
        return $this->status === self::STATUS_USED;
    }

    // Assign card to user
    public function assignToUser($userId)
    {
        $this->update([
            'assigned_user_id' => $userId
        ]);
    }

    // Mark card as used and create usage record
    public function markAsUsed($userId = null)
    {
        $userId = $userId ?? $this->assigned_user_id;
        
        if (!$userId) {
            throw new \Exception('User ID is required to mark card as used');
        }

        DB::transaction(function () use ($userId) {
            // Update card number status
            $this->update([
                'status' => self::STATUS_USED,
                'assigned_user_id' => $userId
            ]);

            // Create usage record
            CardUsage::create([
                'user_id' => $userId,
                'card_number_id' => $this->id,
                'used_at' => now()
            ]);
        });
    }

     // Get status badge class for display
    public function getStatusBadgeClass()
    {
        if ($this->isUsed()) {
            return 'bg-danger';
        } elseif ($this->isAssignedButNotUsed()) {
            return 'bg-warning';
        } else {
            return 'bg-success';
        }
    }

    // Get status text for display
    public function getStatusText()
    {
        if ($this->isUsed()) {
            return __('messages.used');
        } elseif ($this->isAssignedButNotUsed()) {
            return __('messages.assigned_not_used');
        } else {
            return __('messages.available');
        }
    }
}
