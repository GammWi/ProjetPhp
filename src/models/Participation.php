<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:20
 */

namespace wishlist\models;


class Participation extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participants';
    protected $primaryKey = ['liste_id', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;

    public function liste(){
        return $this->belongsTo('\wishlist\models\Liste','liste_id');
    }

    public function user(){
        return $this->belongsTo('\wishlist\models\User','user_id');
    }
}