<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    // кастинг атрибутов
    protected function casts(): array
    {
        return [
            'due_date' => 'date:Y-m-d',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    // связи

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // query scopes

    // Только активные задачи
    public function scopeActive(Builder $query): void
    {
        $query->where('status', '!=', 'done');
    }

    // Только просроченные задачи
    public function scopeOverdue(Builder $query): void
    {
        $query->where('due_date', '<', now())->where('status', '!=', 'done');
    }

    // Последние созданные задачи
    public function scopeRecent(Builder $query, int $limit = 5): void
    {
        $query->orderByDesc('created_at')->limit($limit);
    }
}