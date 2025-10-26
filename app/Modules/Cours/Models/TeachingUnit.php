<?php

namespace App\Modules\Cours\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Relation avec les ECUE (Course Elements)
     */
    public function courseElements()
    {
        return $this->hasMany(CourseElement::class);
    }
}
