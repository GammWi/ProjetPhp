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
        (new v\SingleListeView($liste))->renderFinal();
    }

    /*
     * fonction permettant d'afficher les listes d'un utilisateur
     */
    public function afficherListeUtilisateur($userId) {
        $user = m\User::where('id', '=', $userId)->first();
        (new v\UserListeView($user))->renderFinal();
    }
}