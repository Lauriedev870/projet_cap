<?php

namespace App\Modules\Inscription\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'abbreviation', 'years_count', 'is_lmd', 'type'];

    
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    
}
