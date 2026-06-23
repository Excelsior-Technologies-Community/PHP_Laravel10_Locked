<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Sfolador\Locked\Traits\HasLocks;


class Post extends Model
{

    use HasLocks;


    protected $fillable = [
        'title',
        'description',
    ];



    public function histories()
    {
        return $this->hasMany(
            PostLockHistory::class
        );
    }

}