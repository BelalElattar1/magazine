<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'magazine_id',
        'publisher_id'
    ];

    public function magazine() {
        return $this->belongsTo(Magazine::class);
    }

    public function publisher() {
        return $this->belongsTo(User::class, 'publisher_id');
    }
}
