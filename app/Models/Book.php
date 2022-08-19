<?php

namespace App\Models;

use App\Models\Pivot\BookUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected  $guarded=[];



    public function user()
    {
        return $this->belongsToMany(User::class)

            ->withPivot('status');

    }
}
