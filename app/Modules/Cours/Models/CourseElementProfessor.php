<?php

namespace App\Modules\Cours\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour la table pivot course_element_professor
 * Représente l'assignation d'un professeur à un élément de cours
 */
class CourseElementProfessor extends Model
{
    protected $table = 'course_element_professor';

    protected $fillable = [
        'course_element_id',
        'professor_id',
    ];

    /**
     * Relation avec l'élément de cours
     */
    public function courseElement(): BelongsTo
    {
        return $this->belongsTo(CourseElement::class);
    }

    /**
     * Relation avec le professeur
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\RH\Models\Professor::class);
    }

    /**
     * Relation avec les programmes qui utilisent cette assignation
     */
    public function programs()
    {
        return $this->hasMany(Program::class, 'course_element_professor_id');
    }
}
