<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-05
 * Time: 11:36
 */

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use wishlist\models as m;

class ControleurMessage
{

    public function nouveauMessageListe(){
        $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
        $texte = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

        $user = m\User::where('id', '=', $user_id)->first();
        $liste = m\Liste::where('no', '=', $liste_id)->first();

        if($liste->user_id == $_SESSION['id'] || $user->estParticipant($liste)){
            $messsage = new m\Message();
            $messsage->liste_id = $liste_id;
            $messsage->user_id = $user_id;
            $messsage->message = $texte;
            $messsage->save();
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    public function supprimerMessageListe($message_id){
        $message_id = filter_var($message_id, FILTER_SANITIZE_NUMBER_INT);
        $message = m\Message::where('id', '=', $message_id)->first();
        $user_id = $_SESSION['id'];
        $liste_id = $message->liste->no;
        if($message->user_id == $user_id){
            $message->delete();
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

}