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

    public function creerListe() {
        $l = new m\Liste();
        $l->titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
        $l->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $l->user_id = $_SESSION['id'];
        $l->save();
        return $l;
    }
}