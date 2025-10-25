<?php

namespace Cap\LaravelCoreModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPath extends Model
{
    use HasFactory;

    protected $fillable = ['student_pending_student_id', 'academic_year_id', 'study_level', 'year_decision', 'role_id', 'financial_status', 'cohort'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    public function studentPendingStudent()
    {
        return $this->belongsTo(StudentPendingStudent::class, 'student_pending_student_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
