<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:22
 */

namespace wishlist\models;


class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function listes(){
        return $this->hasMany('\wishlist\models\Liste','user_id');
    }
}