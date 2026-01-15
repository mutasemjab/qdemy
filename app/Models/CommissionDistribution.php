<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_payment_id',
        'course_id',
        'teacher_id',
        'pos_id',
        'course_price',
        'platform_commission_percentage',
        'platform_commission_amount',
        'pos_commission_percentage',
        'pos_commission_amount',
        'distribution_type',
        'platform_final_amount',
        'teacher_final_amount',
        'platform_pos_deduction',
        'teacher_pos_deduction',
        'notes',
    ];

    protected $casts = [
        'course_price' => 'decimal:2',
        'platform_commission_percentage' => 'decimal:2',
        'platform_commission_amount' => 'decimal:2',
        'pos_commission_percentage' => 'decimal:2',
        'pos_commission_amount' => 'decimal:2',
        'platform_final_amount' => 'decimal:2',
        'teacher_final_amount' => 'decimal:2',
        'platform_pos_deduction' => 'decimal:2',
        'teacher_pos_deduction' => 'decimal:2',
    ];

    // Relationships
    public function coursePayment()
    {
        return $this->belongsTo(CoursePayment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function pos()
    {
        return $this->belongsTo(POS::class);
    }
}
