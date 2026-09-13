<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'website', 'notes'];

    /**
     * @phpstan-return HasMany<Contact, self>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @phpstan-return HasMany<Application, self>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
