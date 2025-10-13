<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Doseyat extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];
    protected $casts = [
        'price' => 'double',
    ];

     // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Log all attributes, or specify: ['name', 'price', 'number_of_cards']
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs()
            ->useLogName('Doseyat') // Custom log name
            ->setDescriptionForEvent(fn(string $eventName) => "Doseyat has been {$eventName}");
    }

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
