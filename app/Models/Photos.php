<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    use HasFactory;

    public function topics(){
        return $this->belongsToMany(Topics::class, 'photo_topics', 'photo_id', 'topic_id')
        ->as('data');
    }
    
}
