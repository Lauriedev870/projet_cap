<?php

namespace App\Modules\Cours\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'credits',
        'teaching_unit_id',
    ];

    protected $casts = [
        'credits' => 'integer',
    ];

    /**
     * Relation avec l'unité d'enseignement
     */
    public function teachingUnit()
    {
        return $this->belongsTo(TeachingUnit::class);
    }

    /**
     * Relation many-to-many avec les professeurs
     */
    public function professors()
    {
        return $this->belongsToMany(
            \App\Modules\RH\Models\Professor::class,
            'course_element_professor',
            'course_element_id',
            'professor_id'
        )->withTimestamps();
    }

    /**
     * Relation avec les programmes
     */
    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    /**
     * Relation avec les ressources pédagogiques
     */
    public function resources()
    {
        return $this->hasMany(CourseElementResource::class);
    }
}
