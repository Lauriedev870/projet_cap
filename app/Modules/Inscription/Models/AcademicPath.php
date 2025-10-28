<?php

namespace App\Modules\Inscription\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPath extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['student_pending_student_id', 'academic_year_id', 'study_level', 'year_decision', 'role_id', 'financial_status', 'cohort'];

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
