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

    public function afficherToutesLesListes(){
        (new v\AllListeView())->renderFinal();
    }

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

    public function afficherListeUtilisateurActuel(){
        $this->afficherListeUtilisateur($_SESSION['id']);
    }

    /*
     * fonction permettant d'afficher le créateur liste
     */
    public function afficherCreateurListe() {
        (new v\CreateListeView())->renderFinal();
    }

    public function creerListe() {
        $l = new m\Liste();
        $l->titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
        $l->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $l->user_id = $_SESSION['id'];
        $l->save();
        return $l;
    }

    public function ajouterItem(){
        $id = $_POST['liste_id'];
        $i = new m\Item();
        $i->nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
        $i->descr = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $i->img = filter_var($_POST['image'], FILTER_SANITIZE_STRING);
        $i->tarif = filter_var($_POST['prix'], FILTER_SANITIZE_NUMBER_FLOAT);
        $i->liste_id = $id;
        $i->save();

        $this->afficherListe($id);
    }

    public function supprimerItem($id){
        $i = m\Item::where('id', '=', $id)->first();
        $liste_id = $i->liste_id;
        $i->delete();

        $this->afficherListe($liste_id);
    }

}