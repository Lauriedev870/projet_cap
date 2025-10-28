<?php

namespace App\Modules\Inscription\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'phone',
        'photo',
        'gender',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
