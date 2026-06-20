<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// テストデータを大量に生成するファクトリー機能
use Illuminate\Database\Eloquent\Model;

class ResidentImage extends Model
{
    protected $fillable = ['resident_id', 'image_path'];
}
