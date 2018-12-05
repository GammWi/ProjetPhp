<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-05
 * Time: 13:50
 */

namespace wishlist\controleurs;

use wishlist\models as m;

class ControleurListe
{

    public function afficherListe($id) {
        $listeid = $id;
        $liste = m\Liste::where('no', '=', $listeid)->first();
        echo('Items de la liste : "' . $liste->titre . '"<br>');
        foreach($liste->items as $item){
            echo('</br> - ' . $item->nom . ' (' . $item->descr . ') : ' . $item->tarif . '€');
            echo('<br><img src="../web/img/'. $item->img . '"height="64">');
        }
    }
}