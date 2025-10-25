<?php

namespace App\Modules\Inscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="EntryLevel",
 *     title="Entry Level",
 *     description="Modèle représentant un niveau d'entrée",
 *     @OA\Property(property="id", type="integer", description="ID unique"),
 *     @OA\Property(property="name", type="string", description="Nom du niveau"),
 *     @OA\Property(property="description", type="string", description="Description du niveau"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour")
 * )
 */
class EntryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
}
