<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Laravelに明示的にカラムの一括代入を許可するため以下で$fileableを定義
//extends Modelでcreate()update()delete()などを継承
class Resident extends Model
{
    protected $fillable = [
    //protected:アクセス装飾子。このクラスと継承後の子クラスからはアクセス可能
    //Residentと親のModelクラスからは以下が使用できる。
        'name',
        'job',
        'likes',
        'dislikes',
        'birthplace',
        'age',
        'image_path',
        'bio',
        'moved_out_at',
    ];
    public function images()
    // この関数は「処理」ではなく「宣言」
    //Controllerは「いつ・何を」、Modelは「データとは何か」を書く場所
{
    return $this->hasMany(ResidentImage::class);
}

}
