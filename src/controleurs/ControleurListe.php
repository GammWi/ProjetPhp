<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-05
 * Time: 13:50
 */

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use wishlist\models as m;
use wishlist\views as v;

class ControleurListe
{
    /*
     * fonction permettant d'afficher une liste
     */
    public function afficherListe($lid) {
        $listeid = $lid;
        $liste = m\Liste::where('no', '=', $listeid)->first();
        echo('Items de la liste : "' . $liste->titre . '"<br>');
        foreach($liste->items as $item){
            /*
            echo('</br> - ' . $item->nom . ' (' . $item->descr . ') : ' . $item->tarif . '€');
            echo('<br><img src="../web/img/'. $item->img . '"height="64">');
            */
            $lv = new v\ListeView($liste);
            echo ($lv->render());
        }
    }
}