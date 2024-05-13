<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\TaskStatus;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

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
        return $query->where('status', TaskStatus::complete);
    }

    public function scopeCompleteLastWeek(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::complete)
            ->where('created_at', '>=', Carbon::now()->subWeek());
    }

    public function scopeIncompleteOrOlderThanWeek(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where('status', TaskStatus::incomplete)
                ->orWhere(function ($innerQuery) {
                    $innerQuery->where('status', TaskStatus::complete)
                        ->where('created_at', '<', Carbon::now()->subWeek());
                });
        });
    }
}
