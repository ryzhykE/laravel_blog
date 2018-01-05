<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const IS_ALLOW = 1;
    const DIS_ALLOW = 0;

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function allow()
    {
        $this->status = Comment::IS_ALLOW;
        $this->save();
    }

    public function disAllow()
    {
        $this->status = Comment::DIS_ALLOW;
        $this->save();
    }

    public function toggleStatus()
    {
        if($this->status == Comment::DIS_ALLOW)
        {
            return $this->allow();
        }

        return $this->disAllow();
    }

    public function remove()
    {
        $this->delete();
    }


}
