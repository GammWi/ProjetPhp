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
use wishlist\views as v;

class ControleurItem
{

    /**
     * Fonction permettant d'afficher un item d'une liste
     */
    function afficherItem($id){
        $item = m\Item::where('id', '=', $id)->first();
        $iv = new v\ItemView($item);
        echo ($iv->render());
    }

    /**
     * Fonction permettant de reserver un item, seulement si on est proprietaire de la liste ou participant a la liste
     */
    function reserverItem(){
        $itemId = filter_var($_POST['item_id'], FILTER_SANITIZE_NUMBER_INT);
        $item =  m\Item::where('id', '=', $itemId)->first();
        //$liste = $item->liste;
        $liste = m\Liste::where('no', '=', $item->liste_id)->first();
        if ($liste->user_id == $_SESSION['id'] || ControleurListe::estParticipant($liste, $_SESSION['id'])) {
            $message = filter_var($_POST['reservation_message'],FILTER_SANITIZE_STRING);
            $item->reservation_user = $_SESSION['id'];
            $item->reservation_message = $message;
            $item->save();
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $item->liste_id]));
    }

    /**
     * Fonction permettant d'annuler une reservation, seulement si on est proprietaire de la liste ou participant a la liste
     */
    function annulerReservation($id){
        $item =  m\Item::where('id', '=', $id)->first();

        if ($item->reservation_user == $_SESSION['id']) {
            $item->reservation_user = null;
            $item->save();
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $item->liste_id]));
    }
}