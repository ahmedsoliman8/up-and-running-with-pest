<?php

namespace App\Models;

use App\Models\Pivot\BookUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected  $guarded=[];



    public function users()
    {
        return $this->belongsToMany(User::class, 'book_user')
            ->using(BookUser::class)
            ->withPivot('status')
            ->withTimestamps();
    }
}
