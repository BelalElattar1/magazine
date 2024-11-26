<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'comment',
        'status',
        'subscriber_id',
        'article_id',
    ];

    public function subscriber() {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }
}
