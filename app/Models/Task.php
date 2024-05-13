<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function scopeComplete(Builder $query): Builder
    {
        return $query->where('status', 'complete');
    }
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('status', 'incomplete');
    }
}
