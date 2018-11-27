<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:27
 */

require_once 'vendor/autoload.php';

use mywhishlist\models as m;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$listes = m\Liste::get();
foreach ($listes as $liste) echo '<br>' . $liste->titre;

echo '<br><br>';

$items = m\Item::get();
foreach ($items as $item) echo '<br>' . $item->nom;