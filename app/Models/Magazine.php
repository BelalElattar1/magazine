<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
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
}
