<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 論理削除
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{
    // 論理削除の利用を宣言
    use SoftDeletes;    
}
