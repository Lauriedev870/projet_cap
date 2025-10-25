<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PendingStudent",
 *     title="Pending Student",
 *     description="Modèle représentant un étudiant en attente d'inscription",
 *     @OA\Property(property="id", type="integer", description="ID unique"),
 *     @OA\Property(property="email", type="string", format="email", description="Adresse email"),
 *     @OA\Property(property="first_name", type="string", description="Prénom"),
 *     @OA\Property(property="last_name", type="string", description="Nom de famille"),
 *     @OA\Property(property="phone", type="string", description="Numéro de téléphone"),
 *     @OA\Property(property="entry_level_id", type="integer", description="ID du niveau d'entrée"),
 *     @OA\Property(property="entry_diploma_id", type="integer", description="ID du diplôme d'entrée"),
 *     @OA\Property(property="status", type="string", enum={"pending", "documents_submitted", "approved", "rejected"}, description="Statut de l'inscription"),
 *     @OA\Property(property="submitted_at", type="string", format="date-time", description="Date de soumission"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour"),
 *     @OA\Property(
 *         property="entry_level",
 *         ref="#/components/schemas/EntryLevel",
 *         description="Niveau d'entrée associé"
 *     ),
 *     @OA\Property(
 *         property="entry_diploma",
 *         ref="#/components/schemas/EntryDiploma",
 *         description="Diplôme d'entrée associé"
 *     )
 * )
 */
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
