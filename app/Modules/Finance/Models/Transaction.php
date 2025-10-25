<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'status',
        'amount_paid',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Modules\Inscription\Models\Student::class);
    }
}
