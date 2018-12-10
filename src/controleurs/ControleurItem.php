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

        $item = m\Item::where('id', '=', $id)->first();
        $iv = new v\ItemView($item);
        echo($iv->render());
    }

    public function creerItem($idListe, $nom, $description, $prix){
        $i = new m\Item();
        $i->liste_id = $idListe;
        $i->nom = $nom;
        $i->description = $description;
        $i->tarif = $prix;
        $i->save();
        return $i->id;
    }
}