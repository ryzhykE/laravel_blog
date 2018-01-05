<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Add subscription email
     *
     * @param $email
     * @return static
     */
    public static function add($email)
    {
        $sub = new static;
        $sub->email = $email;
        $sub->save();

        return $sub;
    }

    /**
     * generate token for user
     */
    public function generateToken()
    {
        $this->token = str_random(100);
        $this->save();
    }

    /**
     * remove subscription
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }
}
