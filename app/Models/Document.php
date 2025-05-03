<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    protected $table = 'documents';
    protected $fillable = ['title', 'image'];
    protected $casts = [
        'image' => 'array',
    ];


    public $timestamps = true;
}
