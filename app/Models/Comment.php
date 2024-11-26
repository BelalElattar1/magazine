<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        'status',
        'subscriber_id',
        'article_id',
    ];

    public function subscriber() {
        return $this->belongsTo(User::class, 'subscriber_id');
    }
}
