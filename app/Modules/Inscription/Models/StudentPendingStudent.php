<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPendingStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'pending_student_id',
        'status',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function pendingStudent()
    {
        return $this->belongsTo(PendingStudent::class);
    }
}
