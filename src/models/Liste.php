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

    public function participations(){
        return $this->hasMany('\wishlist\models\Participation', 'liste_id');
    }

    public function messages(){
        return $this->hasMany('\wishlist\models\Message', 'liste_id')->orderBy('created_at');
    }

    public function peutAccederSession($session){
        $res = false;
        $tokenAllow = false;
        if(isset($_SESSION['sessionToken'])){
            foreach ($_SESSION['sessionToken'] as $key => $value){
                if($value == $this->token){
                    $tokenAllow = true;
                }
            }
        }
        if ($this->publique == 1 || $tokenAllow){
            $res = true;
        } else {
            if(isset($session['id'])){
                $u = User::where('id', '=', $session['id'])->first();
                if($u == $this->user || $u->estParticipant($this)){
                    $res = true;
                }
            }
        }
        return $res;
    }

    public function estProprietaireSession($session){
        $res = false;
        if(isset($session['sessionToken'])){
            foreach ($session['sessionToken'] as $key => $value){
                if($value == $this->token){
                    $res = true;
                }
            }
        }
        if(!$res){
            if(isset($_SESSION['id'])){
                if($this->user_id == $_SESSION['id']){
                    $res = true;
                }
            }
        }
        return $res;
    }

    public function estParticipantSession($session){
        $res = false;
        if(isset($session['sessionToken'])){
            foreach ($session['sessionToken'] as $key => $value){
                if($value == $this->token){
                    $res = true;
                }
            }
        }
        if(!$res){
            if(isset($session['id'])){
                $user = User::where('id', '=', $session['id'])->first();
                if($user->estParticipant($this)){
                    $res = true;
                }
                if($this->user_id == $session['id']){
                    $res = true;
                }
            }
        }
        return $res;
    }

    public function destinataire(){
        return $this->belongsTo('\wishlist\models\User','destinataire');
    }
}
