<?php

namespace Cap\LaravelCoreModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'level', 'semester1_credits', 'semester2_credits', 'academic_year_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
