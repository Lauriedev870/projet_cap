<?php

namespace App\Modules\Stockage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'original_name',
        'path',
        'disk',
        'visibility',
        'module_name',
        'module_resource_type',
        'module_resource_id',
        'collection',
        'size',
        'mime_type',
        'extension',
        'file_hash',
        'metadata',
        'is_locked',
        'locked_at',
        'locked_by',
        'download_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'download_count' => 'integer',
    ];

    /**
     * Le propriétaire du fichier.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    /**
     * L'utilisateur qui a verrouillé le fichier.
     */
    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'locked_by');
    }

    /**
     * Les permissions associées à ce fichier.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(FilePermission::class);
    }

    /**
     * Les partages de ce fichier.
     */
    public function shares(): HasMany
    {
        return $this->hasMany(FileShare::class);
    }

    /**
     * L'historique des activités sur ce fichier.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(FileActivity::class);
    }

    /**
     * Obtenir l'URL complète du fichier.
     */
    public function getUrlAttribute(): ?string
    {
        if ($this->visibility === 'public') {
            return Storage::disk($this->disk)->url($this->path);
        }
        
        // Pour les fichiers privés, retourner l'URL de l'API
        return route('api.files.download', ['file' => $this->id]);
    }

    /**
     * Obtenir la taille formatée.
     */
    public function getSizeForHumansAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Vérifie si le fichier existe physiquement.
     */
    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Vérifie si le fichier est une image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Vérifie si le fichier est un document.
     */
    public function isDocument(): bool
    {
        $documentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument'];
        
        foreach ($documentTypes as $type) {
            if (str_starts_with($this->mime_type, $type)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Incrémente le compteur de téléchargements.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Scope pour les fichiers publics.
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope pour les fichiers privés.
     */
    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }

    /**
     * Scope pour les fichiers d'un module spécifique.
     */
    public function scopeForModule($query, string $moduleName, ?string $resourceType = null, ?int $resourceId = null)
    {
        $query->where('module_name', $moduleName);
        
        if ($resourceType !== null) {
            $query->where('module_resource_type', $resourceType);
        }
        
        if ($resourceId !== null) {
            $query->where('module_resource_id', $resourceId);
        }
        
        return $query;
    }

    /**
     * Scope pour les fichiers d'une collection.
     */
    public function scopeInCollection($query, string $collection)
    {
        return $query->where('collection', $collection);
    }

    /**
     * Scope pour les fichiers non verrouillés.
     */
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    /**
     * Scope pour les fichiers verrouillés.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    /**
     * Scope pour rechercher par nom.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('original_name', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    }
}
