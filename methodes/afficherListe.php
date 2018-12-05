<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 11:04
 */

require_once '../vendor/autoload.php';

use wishlist\models as m;
/*
$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('../src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
*/
$listeid = -1;
if ( isset($_GET['liste']) ) {
    if (!is_null( $_GET['liste'])){
        $listeid = $_GET['liste'];
    }
}
if($listeid != -1){
    $liste = m\Liste::where('no', '=', $listeid)->first();
    echo('Items de la liste : "' . $liste->titre . '"<br>');
    foreach($liste->items as $item){
        echo('</br> - ' . $item->nom . ' (' . $item->descr . ') : ' . $item->tarif . '€');
        echo('<br><img src="../img/'. $item->img . '"height="64">');
    }
} else {
    echo('Utiliser : afficherListe.php?liste=ID' . '<br>');
}