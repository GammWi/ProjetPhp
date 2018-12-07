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

    /*
     * Fonction permettant d'afficher un item d'une liste
     */
    function afficherItem($id){

        $itemid = $id;
        $item = m\Item::where('id', '=', $itemid)->first();
        /*
        echo($item->nom . ' (' . $item->descr . ') : ' . $item->tarif . '€');
        echo('<br><img src="../web/img/'. $item->img . '"height="64">');
        */
        $iv = new v\ItemView($item);
        echo($iv->render());
    }
}