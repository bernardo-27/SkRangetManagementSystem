<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    protected $fillable = [
        'name', 'position', 'term_start', 'term_end', 'email',
        'phone', 'birthdate', 'achievements', 'photo'
    ];

}
