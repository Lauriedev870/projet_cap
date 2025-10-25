<?php

namespace App\Modules\Stockage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileActivity extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Pas de colonne updated_at

    protected $fillable = [
        'file_id',
        'user_id',
        'activity_type',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Le fichier concerné.
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * L'utilisateur qui a effectué l'action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    /**
     * Scope pour filtrer par type d'activité.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope pour les activités d'un utilisateur.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour les activités récentes.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Crée une nouvelle activité.
     */
    public static function log(
        int $fileId,
        ?int $userId,
        string $activityType,
        ?string $description = null,
        ?array $metadata = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return static::create([
            'file_id' => $fileId,
            'user_id' => $userId,
            'activity_type' => $activityType,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }
}
