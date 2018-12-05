<?php
/**
 * File:  index.php
 * Creation Date: 04/12/2017
 */

require_once 'vendor/autoload.php';

use wishlist\controleurs as c;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();


$app = new \Slim\Slim();

$app->get('/', function () {
    $app = \Slim\Slim::getInstance();
    echo "Projet PHP : MyWishlist </br>";
    echo '<a href="'.$app->urlFor('afficherItem', ['id' => 2]) . '"> Afficher un item</a></br>';
    echo '<a href="'.$app->urlFor('afficherListe', ['lid' => 3]) . '"> Afficher une liste</a></br>';
});

$app->get('/item/:id', function ($id) {
    (new c\ControleurItem())->afficherItem($id);
})->name('afficherItem');

$app->get('/liste/:lid', function ($lid) {
    (new c\ControleurListe())->afficherListe($lid);
})->name('afficherListe');

$app->run();