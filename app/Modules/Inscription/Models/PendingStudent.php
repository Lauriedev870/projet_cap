<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'entry_level_id',
        'entry_diploma_id',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function entryLevel()
    {
        return $this->belongsTo(EntryLevel::class);
    }

    public function entryDiploma()
    {
        return $this->belongsTo(EntryDiploma::class);
    }

    public function studentPendingStudents()
    {
        return $this->hasMany(StudentPendingStudent::class);
    }

    /**
     * Get the files associated with this pending student.
     */
    public function files()
    {
        return $this->hasMany(\App\Modules\Stockage\Models\File::class, 'module_resource_id')
            ->where('module_name', 'inscription')
            ->where('module_resource_type', 'pending_student');
    }
}
