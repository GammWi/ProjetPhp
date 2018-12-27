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
        (new v\SingleListeView($l))->renderFinal();
    }

    public function supprimerItem($id){
        $i = m\Item::where('id', '=', $id)->first();
        $liste_id = $i->liste_id;
        $i->delete();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    public function ajouterParticipant(){
        $user = m\User::where('email', '=', filter_var($_POST['email'], FILTER_SANITIZE_EMAIL))->first();
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
        $p = new m\Participation();
        $p->liste_id = $liste_id;
        $p->user_id = $user->id;
        $p->save();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $liste_id]));
    }

    public function supprimerParticipant($liste_id, $user_id){
        m\Participation::where([['liste_id', '=', $liste_id], ['user_id', '=', $user_id]])->delete();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    public function ajouterItem(){
        //FICHIER DE L'IMAGE
        $uploaded = false;
        $target_dir = "web/uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        //Verification du type de fichier (image)
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        //Vérification de la taille du fichier
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $uploadOk = 0;
        }
        //Vérification du format du fichier
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }
        //Si toutes les vérifications sont bonnes
        if ($uploadOk != 0) {
            //AJOUT DE L'ITEM POUR OBTENIR L'ID
            $id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
            $i = new m\Item();
            $i->nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $i->descr = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $i->tarif = filter_var($_POST['prix'], FILTER_SANITIZE_NUMBER_FLOAT);
            $i->liste_id = $id;
            $i->save();

            //Verification de l'upload de l'image
            $target_file = $target_dir . "item" . $i->id . "." . $imageFileType;
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $i->img = "/" . $target_file;
                $i->save();
            } else {
                $i->delete();
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $id)));
    }

    public function renommerUneListe(){
        //On recupere l'id de la liste
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
        //On recupere la liste assosiciee
        $liste = m\Liste::where('no', '=', $liste_id)->first();
        //On recupere le nouveau titre
        $liste->titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
        $liste->save();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $liste_id]));
    }
}
