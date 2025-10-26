<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'program_id',
        'study_level',
        'group_name',
    ];

    /**
     * Relation avec l'année académique
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relation avec la filière (Program dans Inscription)
     */
    public function program()
    {
        return $this->belongsTo(\App\Modules\Inscription\Models\Program::class);
    }

    /**
     * Relation avec les programmes de cours
     */
    public function coursePrograms()
    {
        return $this->hasMany(\App\Modules\Cours\Models\Program::class);
    }

    /**
     * Relation avec les groupes d'étudiants
     */
    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class, 'class_group_id');
    }
}
