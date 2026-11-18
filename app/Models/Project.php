<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    protected $fillable = ['name', 'description'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    // HasMany: У проекта много задач
    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }

    // BelongsToMany: У проекта много тегов
    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    // MorphMany: Полиморфная связь/ комментарии к проекту
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }
}