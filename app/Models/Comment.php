<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = ['body', 'commentable_id', 'commentable_type'];

    // Полиморфная связь: комментарий может принадлежать любой модели
    public function commentable(): MorphTo {
        return $this->morphTo();
    }
}
