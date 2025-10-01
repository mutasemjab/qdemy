<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

     protected $casts = [
        'amount' => 'double'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return $this->attributes['type'] == 1 ? __('messages.add_money') : __('messages.withdrawal');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2);
    }

    // Scopes
    public function scopeAddMoney($query)
    {
        return $query->where('type', 1);
    }

    public function scopeWithdrawal($query)
    {
        return $query->where('type', 2);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
