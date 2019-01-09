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

    /**
     * Fonction permettant d'afficher toutes les listes que l'utilisateur de la session peut voir
     */
    public function afficherToutesLesListes(){
        $listesAffichables = array();
        $listes = m\Liste::get();
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $userId = $_SESSION['id'];
            $user = m\User::where('id', '=', $userId)->first();
            foreach($listes as $l) {
                if ($l->peutAcceder($user)) {
                    $listesAffichables[] = $l;
                }
            }
        } else {
            foreach($listes as $l) {
                if ($l->publique == 1) {
                    $listesAffichables[] = $l;
                }
            }
        }
        (new v\MultiplesListesView($listesAffichables, "Toutes les listes"))->renderFinal();
    }

    /**
     * Fonction permettant d'afficher une liste
     */
    public function afficherListe($lid) {
        $listeid = $lid;
        $liste = m\Liste::where('no', '=', $listeid)->first();
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $userId = $_SESSION['id'];
            $user = m\User::where('id', '=', $userId)->first();
            $res = $liste->peutAcceder($user);
        } else {
            $res = $liste->publique;
        }
        if ($res){
            (new v\SingleListeView($liste))->renderFinal();
        } else {
            (new v\ErreurView("Vous ne pouvez pas accéder à cette liste (liste privée)"))->renderFinal();
        }
    }

    /**
     * Fonction permettant d'afficher les listes d'un utilisateur
     */
    public function afficherListeUtilisateur($userId) {
        $user = m\User::where('id', '=', $userId)->first();
        $listes = $user->listes;
        $listesAffichables = array();
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $userIdRegarder = $_SESSION['id'];
            $userRegarder = m\User::where('id', '=', $userIdRegarder)->first();
            foreach($listes as $l){
                if($l->peutAcceder($userRegarder)){
                    $listesAffichables[] = $l;
                }
            }
        } else {
            foreach($listes as $l){
                if($l->publique == 1){
                    $listesAffichables[] = $l;
                }
            }
        }
        $viewName = "Listes de $user->name";
        (new v\MultiplesListesView($listesAffichables, $viewName))->renderFinal();
    }

    public function afficherListeUtilisateurActuel(){
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $this->afficherListeUtilisateur($_SESSION['id']);
        } else {
            (new v\ErreurView("Vous n'êtes pas connecté"))->renderFinal();
        }
    }

    /**
     * Fonction permettant d'afficher le créateur liste
     */
    public function afficherCreateurListe() {
        (new v\CreateListeView())->renderFinal();
    }

    public function creerListe() {
        $l = new m\Liste();
        $l->titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
        $l->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $l->user_id = $_SESSION['id'];
        }
        $l->token = uniqid();
        $l->save();
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $l->no)));
    }

    public function supprimerItem($id){
        $i = m\Item::where('id', '=', $id)->first();
        $liste = $i->liste;
        $liste_id = $i->liste_id;
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $user = m\User::where('id', '=', $_SESSION['id'])->first();
            //PROTECTION PERMISSIONS
            if($liste->user_id == $_SESSION['id'] || $user->estParticipant($liste)){
                $i->delete();
            }
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    public function ajouterParticipant(){
        $user = m\User::where('email', '=', filter_var($_POST['email'], FILTER_SANITIZE_EMAIL))->first();
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $l = m\Liste::where("no", "=", $liste_id)->first();
            //PROTECTION PERMISSIONS
            if ($l->user_id == $_SESSION["id"]) {
                $p = new m\Participation();
                $p->liste_id = $liste_id;
                $p->user_id = $user->id;
                $p->save();
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $liste_id]));
    }

    /**
     * Fonction permettant de supprimer un participant d'une liste, cela est possible seulement si l'utilisateur est celui qui a créé la liste
     * @param $liste_id
     * @param $user_id
     */
    public function supprimerParticipant($lid, $uid){
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $liste_id = filter_var($lid, FILTER_SANITIZE_NUMBER_INT);
            $user_id = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);
            $l = m\Liste::where("no", "=", $liste_id)->first();
            //PROTECTION PERMISSIONS
            if ($l->user_id == $_SESSION["id"] || $user_id == $_SESSION["id"]) {
                m\Participation::where([['liste_id', '=', $liste_id], ['user_id', '=', $user_id]])->delete();
            }
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    public function renommerUneListe(){
        //On recupere l'id de la liste
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            //On recupere la liste assosiciee
            $liste = m\Liste::where('no', '=', $liste_id)->first();
            //On recupere le nouveau titre
            //PROTECTION PERMISSIONS
            if ($liste->user_id == $_SESSION["id"]) {
                $liste->titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
                $liste->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                $liste->save();
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', ['lid' => $liste_id]));
    }

    public function supprimerUneListe(){
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            //On recupere l'id de la liste
            $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
            //On recupere la liste assosiciee
            $liste = m\Liste::where('no', '=', $liste_id)->first();
            //PROTECTION PERMISSIONS
            if ($liste->user_id == $_SESSION["id"]) {
                $liste->delete();
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherToutesLesListes'));
    }

    public function ajouterItem(){
        //VERIFICATION DES PERMISSIONS DE L'UTILISATEUR
        $liste_id = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $liste = m\Liste::where('no', '=', $liste_id)->first();

            $user = m\User::where('id', '=', $_SESSION['id'])->first();
            //PROTECTION PERMISSIONS
            if($liste->user_id == $_SESSION['id'] || $user->estParticipant($liste)){

                //FICHIER DE L'IMAGE
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
                    $i = new m\Item();
                    $i->nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                    $i->descr = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                    $i->tarif = filter_var($_POST['prix'], FILTER_SANITIZE_NUMBER_FLOAT);
                    $i->liste_id = $liste_id;
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
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste_id)));
    }

    /**
     * Methode permettant de rendre la liste cournate publique
     */
    public function rendrePublique(){
        $listeId = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
        $liste = m\Liste::where('no', '=', $listeId)->first();
        if ($liste->user_id == $_SESSION['id']){
            $liste->publique = 1;
            $liste->save();
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $listeId)));
    }

    /**
     * Methode permettant de rendre la liste privee
     */
    public function rendrePrivee(){
        $listeId = filter_var($_POST['liste_id'], FILTER_SANITIZE_NUMBER_INT);
        $liste = m\Liste::where('no', '=', $listeId)->first();
        if ($liste->user_id == $_SESSION['id']){
            $liste->publique = 0;
            $liste->save();
        }
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $listeId)));
    }

    public function associerListe() {
        $liste_token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
        $liste = m\Liste::where('token', '=', $liste_token)->first();

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $liste->user_id = $_SESSION['id'];
            $liste->token = uniqid();
            $liste->save();
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherListe', array('lid' => $liste->no)));
    }
}
