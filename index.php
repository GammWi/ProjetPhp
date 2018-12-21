<?php
/**
 * File:  index.php
 * Creation Date: 04/12/2017
 */
require_once 'vendor/autoload.php';

session_start();

use wishlist\controleurs as c;
use wishlist\views as v;
use wishlist\models as m;

if(!empty($_SESSION))
{

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$app = new \Slim\Slim();

$app->get('/', function () {
    (new v\AllListeView(m\Liste::where('no', '=', 1)->first()))->renderFinal();
});

$app->get('/liste/:lid', function ($lid) {
    (new c\ControleurListe())->afficherListe($lid);
})->name('afficherListe');

$app->get('/userListes/:userId', function ($userId) {
    (new c\ControleurListe())->afficherListeUtilisateur($userId);
})->name('afficherListe');

$app->get('/item/:id', function ($id) {
    (new c\ControleurItem())->afficherItem($id);
})->name('afficherItem');

$app->get('/createListe', function () {
    (new c\ControleurCreationListe())->afficherCreateurListe();
})->name('createListe');

$app->get('/insertNewListe', function () {
	(new c\ControleurCreationListe())->creerListe('Titre','Desc',$_SESSION['id']);
})->name('insertNewListe');

$app->get('/afficherMyProfile', function () {
    (new c\ControleurProfile())->afficherProfile($_SESSION['id']);
})->name('afficherMyProfile');

$app->get('/afficherProfile/:id', function ($id) {
    (new c\ControleurProfile())->afficherProfile($id);
})->name('afficherProfile');

$app->run();
}
else
{
	header("Location:login.php");
}
