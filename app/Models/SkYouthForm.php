<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYouthForm extends Model {
    use HasFactory;

    protected $table = 'sk_youth_form';


    protected $fillable = [
        'user_id',
        'full_name',
        'dob',
        'gender',
        'national_id',
        'address',
        'phone',
        'email',
        'education',
        'school_name',
        'voter_status',
        'voter_id',
        'youth_org',
        'skills',
        'volunteer',
        'guardian_name',
        'guardian_contact',
        'profile_picture',
    ];


}    
