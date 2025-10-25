<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'level',
        'academic_year_id',
        'amount',
        'sponsored_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sponsored_amount' => 'decimal:2',
    ];

    public function program()
    {
        return $this->belongsTo(\App\Modules\Inscription\Models\Program::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(\App\Modules\Inscription\Models\AcademicYear::class);
    }
}
