<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2019-01-07
 * Time: 09:30
 */

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use wishlist\models as m;

class ControleurMembres
{

    public function afficherTousLesMembres(){
        (new v\MembresListeView())->renderFinal();
    }
}