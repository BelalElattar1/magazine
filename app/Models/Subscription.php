<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'status',
        'type',
        'start',
        'end',
        'subscriber_id',
        'magazine_id'
    ];

    public function magazine() {
        return $this->belongsTo(Magazine::class);
    }

    public function subscriber() {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }
}
