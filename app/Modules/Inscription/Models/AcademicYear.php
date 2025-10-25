<?php

namespace Cap\LaravelCoreModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['submission_start', 'submission_end', 'academic_year', 'year_start', 'year_end'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function submissionPeriod()
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
