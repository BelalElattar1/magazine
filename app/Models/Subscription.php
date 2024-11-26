<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
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
}
