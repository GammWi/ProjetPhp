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
     * Fonction permettant de reserver un item
     */
    function reserverItem($id){
        $item =  m\Item::where('id', '=', $id)->first();
        $item->reservation_user = $_SESSION['id'];
        $item->save();
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $item->liste_id]));
    }

    /**
     * Fonction permettant d'annuler une reservation
     */
    function annulerReservation($id){
        $item =  m\Item::where('id', '=', $id)->first();
        $liste = $item->liste;
        
        $item->reservation_user = null;
        $item->save();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $item->liste_id]));
    }
}