<?php

namespace Cap\LaravelCoreModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'abbreviation', 'years_count', 'is_lmd', 'type'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    
}
