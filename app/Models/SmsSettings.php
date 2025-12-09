<?php

// File: app/Models/SmsSettings.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'api_key',
        'enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'api_key' => 'encrypted',
        'enabled' => 'boolean',
    ];

    /**
     * Get the tenant that owns the SMS settings.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

// SMS Settings model - lagrer Teletopia API nøkkel per tenant.
// API-nøkkel krypteres automatisk i databasen via 'encrypted' cast.
// Enabled boolean indikerer om SMS-funksjonalitet er aktivert for tenant.
