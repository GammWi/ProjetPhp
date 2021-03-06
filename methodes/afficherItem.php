<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 11:19
 */

require_once 'vendor/autoload.php';

use wishlist\models as m;

$itemid = -1;
if ( isset($_GET['item']) ) {
    if (!is_null( $_GET['item'])){
        $itemid = $_GET['item'];
    }
}
if($itemid != -1){
    $item = m\Item::where('id', '=', $itemid)->first();
    echo('Nom : ' . $item->nom . '<br>');
    echo('Prix : ' . $item->tarif . '<br>');
    echo('Description : ' . $item->descr . '<br>');
    echo('<img src="../web/img/'. $item->img . '"height="64">');
} else {
    echo('Utiliser : afficherItem.php?item=ID' . '<br>');
}