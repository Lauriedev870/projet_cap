<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="EntryDiploma",
 *     title="Entry Diploma",
 *     description="Modèle représentant un diplôme d'entrée",
 *     @OA\Property(property="id", type="integer", description="ID unique"),
 *     @OA\Property(property="name", type="string", description="Nom du diplôme"),
 *     @OA\Property(property="uuid", type="string", format="uuid", description="UUID unique"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour")
 * )
 */
class EntryDiploma extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
