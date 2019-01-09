<?php
/**
 * File:  index.php
 * Creation Date: 04/12/2017
 */
require_once 'vendor/autoload.php';

session_start();
if(isset($_COOKIE['rememberMe']))
{
	$rememberMe = unserialize($_COOKIE['rememberMe']);
	$id = $rememberMe['id'];
	$name = $rememberMe['name'];
	$ip = $rememberMe['ip'];
	$_SESSION['id']=$id;
	$_SESSION['name']=$name;
}
use wishlist\controleurs as c;
use wishlist\views as v;
use wishlist\models as m;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$app = new \Slim\Slim();

$app->get('/', function () {
    (new c\ControleurListe())->afficherToutesLesListes();
})->name('home');

$app->get('/listes', function () {
    (new c\ControleurListe())->afficherToutesLesListes();
})->name('afficherToutesLesListes');

$app->get('/liste/:lid', function ($lid) {
    (new c\ControleurListe())->afficherListe($lid);
})->name('afficherListe');

$app->get('/myListes', function () {
    (new c\ControleurListe())->afficherListeUtilisateurActuel();
})->name('afficherListesUserActuel');

$app->get('/userListes/:userId', function ($userId) {
    (new c\ControleurListe())->afficherListeUtilisateur($userId);
})->name('afficherListesUserId');

$app->get('/createListe', function () {
    (new c\ControleurListe())->afficherCreateurListe();
})->name('createListe');

$app->post('/insertNewListe', function () {
	(new c\ControleurListe())->creerListe();
})->name('insertNewListe');

$app->post('/addItem', function () {
    (new c\ControleurListe())->ajouterItem();
})->name('addItem');

$app->get('/deleteItem/:id', function ($id) {
    (new c\ControleurListe())->supprimerItem($id);
})->name('deleteItem');

$app->post('/addParticipant', function () {
    (new c\ControleurListe())->ajouterParticipant();
})->name('addParticipant');

$app->get('/deleteParticipant/:lid/:uid', function ($lid, $uid) {
    (new c\ControleurListe())->supprimerParticipant($lid, $uid);
})->name('deleteParticipant');

$app->get('/afficherMyProfile', function () {
    (new c\ControleurProfile())->afficherMonProfile();
})->name('afficherMyProfile');

$app->get('/afficherProfile/:id', function ($id) {
    (new c\ControleurProfile())->afficherProfile($id);
})->name('afficherProfile');

$app->get('/listeMembres', function () {
    //TODO
    (new v\MembresListeView())->renderFinal();
})->name('afficherMembres');

$app->post('/renommerLaListe', function () {
    (new c\ControleurListe())->renommerUneListe();
})->name('renommerLaListe');

$app->post('/suppressionDUneListe', function () {
    (new c\ControleurListe())->supprimerUneListe();
})->name('supprimerListe');

$app->post('/updateProfileInfos', function () {
    (new c\ControleurProfile())->updateProfileInformations();
})->name('updateProfileInfos');

$app->post('/reserverItem/:id', function () {
    (new c\ControleurItem())->reserverItem();
})->name('ReserverUnItem');

$app->post('/updateProfilePhoto', function () {
    (new c\ControleurProfile())->updateProfilePhoto();
})->name('updateProfilePhoto');

$app->get('/annulerReservation/:id', function ($id) {
    (new c\ControleurItem())->annulerReservation($id);
})->name('AnnulerUneReservation');

$app->post('/nouveauMessageListe', function () {
    (new c\ControleurMessage())->nouveauMessageListe();
})->name('nouveauMessageListe');

$app->post('/rendrePublique', function () {
    (new c\ControleurListe())->rendrePublique();
})->name('rendrePublique');

$app->post('/rendrePrivee', function () {
    (new c\ControleurListe())->rendrePrivee();
})->name('rendrePrivee');

$app->get('/supprimerMessage/:id', function ($id) {
    (new c\ControleurMessage())->supprimerMessageListe($id);
})->name('supprimerMessage');

$app->post('/associerListe', function () {
    (new c\ControleurListe())->associerListe();
})->name('associerListe');

$app->run();

