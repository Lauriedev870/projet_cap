<?php

namespace App\Modules\Stockage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FileShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'token',
        'password_hash',
        'allow_download',
        'allow_preview',
        'max_downloads',
        'download_count',
        'expires_at',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'allow_download' => 'boolean',
        'allow_preview' => 'boolean',
        'max_downloads' => 'integer',
        'download_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Génère automatiquement un token unique lors de la création.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }
        });
    }

    /**
     * Le fichier partagé.
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Le créateur du partage.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Obtenir l'URL complète du partage.
     */
    public function getShareUrlAttribute(): string
    {
        return route('api.files.share.access', ['token' => $this->token]);
    }

    /**
     * Vérifie si le partage a expiré.
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Vérifie si le partage a atteint la limite de téléchargements.
     */
    public function hasReachedDownloadLimit(): bool
    {
        if (!$this->max_downloads) {
            return false;
        }

        return $this->download_count >= $this->max_downloads;
    }

    /**
     * Vérifie si le partage est valide (actif, non expiré, non limité).
     */
    public function isValid(): bool
    {
        return $this->is_active 
            && !$this->isExpired() 
            && !$this->hasReachedDownloadLimit();
    }

    /**
     * Vérifie le mot de passe du partage.
     */
    public function checkPassword(?string $password): bool
    {
        if (!$this->password_hash) {
            return true; // Pas de mot de passe requis
        }

        if (!$password) {
            return false;
        }

        return password_verify($password, $this->password_hash);
    }

    /**
     * Incrémente le compteur de téléchargements.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Scope pour les partages actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope pour les partages expirés.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope pour les partages par token.
     */
    public function scopeByToken($query, string $token)
    {
        return $query->where('token', $token);
    }
}
