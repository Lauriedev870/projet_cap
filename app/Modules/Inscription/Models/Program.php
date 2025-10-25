<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cycle_id',
        'department_id',
        'description',
        'duration_years',
    ];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
