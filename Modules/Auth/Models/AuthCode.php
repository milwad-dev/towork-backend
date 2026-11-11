<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Enums\AuthCodePurpose;

class AuthCode extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['email', 'purpose', 'code', 'attempts', 'expires_at'];

    protected $hidden = ['code'];

    protected function casts(): array
    {
        return [
            'purpose' => AuthCodePurpose::class,
            'expires_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
