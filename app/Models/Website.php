<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Website extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'public_key',
        'secret_key',
        'webhook_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Website $website) {
            if (empty($website->public_key)) {
                $website->public_key = 'pk_' . Str::random(32);
            }
            if (empty($website->secret_key)) {
                $website->secret_key = 'sk_' . Str::random(32);
            }
        });
    }

    public function gatewaySettings(): HasMany
    {
        return $this->hasMany(WebsiteGatewaySetting::class);
    }

    public function getSettingFor(string $gateway): ?WebsiteGatewaySetting
    {
        return $this->gatewaySettings()->where('gateway', $gateway)->first();
    }
}