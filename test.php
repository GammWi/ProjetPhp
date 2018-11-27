<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:27
 */

require_once 'vendor/autoload.php';

use wishlist\models as m;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

//Mes listes
$listes = m\Liste::get();
foreach ($listes as $liste) echo '<br>' . $liste->titre . '(' . $liste->no . ')';

echo '<br><br>';

//Mes items
$items = m\Item::get();
foreach ($items as $item) echo '<br>' . $item->nom;

//Item dont l'id est passé en parmaetre
$id = 1;
if ( isset($_GET['id']) ) {
    if (!is_null( $_GET['id'])){
        $id = $_GET['id'];
    }
} //? $_GET('id') : 1;

$MyItem = m\Item::where('id', '=', $id)->first();

var_dump($_GET['id']);

echo "Votre item ($id) : " . $MyItem->nom;
