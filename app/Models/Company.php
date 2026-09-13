<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'website', 'notes'];

    /**
     * @return HasMany<Contact, Company>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return HasMany<Application, Company>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
