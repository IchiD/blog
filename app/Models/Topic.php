<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        "name", "content"
    ];

    // usersとの一対多のリレーションメソッドを定義
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
