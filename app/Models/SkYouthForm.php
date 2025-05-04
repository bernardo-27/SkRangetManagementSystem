<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SkYouthForm extends Model
{
    // Your existing model properties and methods...
    
    protected $table = 'sk_youth_form';
    
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'dob',
        'gender',
        'address',
        'email',
        'phone',
        'education',
        'school_name',
        'voter_status',
        'youth_org',
        'skills',
        'volunteer',
        'guardian_name',
        'guardian_contact',
        'profile_picture',
        'national_id',
        'voter_id',
    ];
    
    /**
     * Get the URL for the profile picture
     * 
     * @return string
     */
    public function getProfilePictureUrlAttribute()
    {
        if (!$this->profile_picture) {
            return asset('images/default-profile.png');
        }
        
        return Storage::disk('rangetsystem')->url($this->profile_picture);
    }
    
    /**
     * Get the URL for the national ID
     * 
     * @return string|null
     */
    public function getNationalIdUrlAttribute()
    {
        if (!$this->national_id) {
            return null;
        }
        
        return Storage::disk('rangetsystem')->url($this->national_id);
    }
    
    /**
     * Get the URL for the voter ID
     * 
     * @return string|null
     */
    public function getVoterIdUrlAttribute()
    {
        if (!$this->voter_id) {
            return null;
        }
        
        return Storage::disk('rangetsystem')->url($this->voter_id);
    }
}
