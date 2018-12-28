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

    public function participations(){
        return $this->hasMany('\wishlist\models\Participation', 'user_id');
    }

    public function messages(){
        return $this->hasMany('\wishlist\models\Message', 'user_id')->orderBy('created_at');
    }

    public function estParticipant($liste) {
        $estParticipant = false;
        foreach($liste->participations as $participation){
            if($participation->user->id == $this->id){
                $estParticipant = true;
            }
        }
        return $estParticipant;
    }
}
