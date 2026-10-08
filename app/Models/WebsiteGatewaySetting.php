<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class WebsiteGatewaySetting extends Model
{
    protected $fillable = [
        'website_id',
        'gateway',
        'is_enabled',
        'mode',
        'sandbox_credentials',
        'live_credentials',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function getCredentials(string $mode): array
    {
        $column = $mode === 'live' ? 'live_credentials' : 'sandbox_credentials';
        $raw = $this->{$column};

        if (!$raw) {
            return [];
        }

        try {
            return json_decode(Crypt::decryptString($raw), true) ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function setCredentials(string $mode, array $credentials): void
    {
        $column = $mode === 'live' ? 'live_credentials' : 'sandbox_credentials';
        $this->{$column} = Crypt::encryptString(json_encode($credentials));
    }
}