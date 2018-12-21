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

class ControleurCreationListe
{
    /*
     * fonction permettant d'afficher le créateur liste
     */
    public function afficherCreateurListe() {
        (new v\CreateListeView())->renderFinal();
    }

    public function creerListe($titre, $description, $userid) {
        $l = new m\Liste();
        $l->titre = $titre;
        $l->description = $description;
        $l->user_id = $userid;
        $l->save();
        return $l;
    }
}