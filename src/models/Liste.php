<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:22
 */

namespace wishlist\models;


class Liste extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'liste';
    protected $primaryKey = 'no';
    public $timestamps = false;

    public function items(){
        return $this->hasMany('\wishlist\models\Item','liste_id');
    }

    public function user(){
        return $this->belongsTo('\wishlist\models\User','user_id');
    }

    public function participants(){
        return User::join('participants', 'user.id', '=', 'user_id')->where('liste_id', '=', $this->no)->get();
    }
}