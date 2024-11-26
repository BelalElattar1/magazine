<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use LogsActivity;
    
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }
}
