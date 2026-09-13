<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = ['company_id', 'name', 'email', 'phone', 'position'];

    /**
     * @phpstan-return BelongsTo<Company, self>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @phpstan-return HasMany<Application, self>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
