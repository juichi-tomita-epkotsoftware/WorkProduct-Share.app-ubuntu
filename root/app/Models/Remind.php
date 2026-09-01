<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remind extends Model
{
    protected $fillable =[
        'title',
        'comment',
        'image_path',
        'category',
        'remind_date',
    ];

    public const CATEGORIES = [
    'kitchen',
    'Shower Room',
    'Work Space',
    'Trash',
    'The other'
];

}

