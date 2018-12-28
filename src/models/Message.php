<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:22
 */

namespace wishlist\models;

class Message extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'liste_message';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function liste(){
        return $this->belongsTo('\wishlist\models\Liste','liste_id');
    }

    public function user(){
        return $this->belongsTo('\wishlist\models\User','user_id');
    }
}