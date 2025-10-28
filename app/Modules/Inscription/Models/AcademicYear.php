<?php

namespace App\Modules\Inscription\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['submission_start', 'submission_end', 'academic_year', 'year_start', 'year_end', 'is_current'];

    protected $casts = [
        'submission_start' => 'date',
        'submission_end' => 'date',
        'is_current' => 'boolean',
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function submissionPeriod()
    {
        return $this->hasMany(SubmissionPeriod::class);
    }
    
    public function submissionPeriods()
    {
        return $this->hasMany(SubmissionPeriod::class);
    }

    public function reclamationPeriod()
    {
        return $this->hasMany(ReclamationPeriod::class);
    }

    public function academicPath()
    {
        return $this->hasMany(AcademicPath::class);
    }

    public function defenseSubmissionPeriods()
    {
        return $this->hasMany(DefenseSubmissionPeriod::class);
    }

    public function minimalAverage()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
