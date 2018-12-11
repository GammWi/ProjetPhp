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

class ControleurProfile
{
    /*
     * fonction permettant un profile
     */
    public function afficherProfile($lid) {
        $listeid = $lid;
        $liste = m\Liste::where('no', '=', $listeid)->first();
        (new v\SingleListeView($liste))->renderFinal();
    }
}