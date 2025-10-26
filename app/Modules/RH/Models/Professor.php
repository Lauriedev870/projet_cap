<?php

namespace App\Modules\RH\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Professor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'phone',
        'password',
        'role_id',
        'rib_number',
        'rib',
        'ifu_number',
        'ifu',
        'bank',
        'status',
        'grade_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relation avec le grade
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Relation many-to-many avec les éléments de cours
     */
    public function courseElements()
    {
        return $this->belongsToMany(
            CourseElement::class,
            'course_element_professor',
            'professor_id',
            'course_element_id'
        )->withTimestamps();
    }

    /**
     * Scope pour les professeurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Obtenir le nom complet
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
