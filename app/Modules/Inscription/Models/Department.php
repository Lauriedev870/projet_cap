<?php

namespace Cap\LaravelCoreModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cycle_id', 'abbreviation', 'next_level_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
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
}
