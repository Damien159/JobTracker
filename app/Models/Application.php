<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'contact_id',
        'job_title',
        'application_date',
        'job_posting_url',
        'notes',
        'tags',
        'desired_salary',
        'application_type',
        'source',
    ];

    protected $casts = [
        'application_date' => 'date',
        'desired_salary' => 'decimal:2',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @phpstan-return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @phpstan-return BelongsTo<Company, self>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @phpstan-return BelongsTo<Contact, self>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @phpstan-return HasMany<ApplicationStatusHistory, self>
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class)->orderByDesc('changed_at');
    }

    /**
     * @phpstan-return HasMany<ApplicationDocument, self>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function currentStatus(): ?string
    {
        return $this->statusHistories()->first()?->status;
    }
}
