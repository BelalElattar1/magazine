<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Magazine extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'publisher_id'
    ];

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
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
