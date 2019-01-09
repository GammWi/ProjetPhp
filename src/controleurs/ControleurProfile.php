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

class ControleurProfile
{

    /*
     * fonction permettant un profile
     */
    public function afficherMonProfile() {
        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            $user = m\User::where('id', '=', $_SESSION['id'])->first();
            (new v\MyProfileView($user))->renderFinal();
        } else {
            (new v\ErreurView("Vous n'êtes pas connecté"))->renderFinal();
        }
    }

    public function afficherProfile($id) {
        $user = m\User::where('id', '=', $id)->first();
        (new v\MyProfileView($user))->renderFinal();
    }

    public function updateProfileInformations(){
        //On recupere le profil
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        //On recupere la liste assosiciee
        $profil = m\User::where('id', '=', $id)->first();

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            //PROTECTION PERMISSIONS
            if($profil->id == $_SESSION['id']){
                //On recupere le nouveau titre
                $profil->name = filter_var($_POST['pseudonyme'], FILTER_SANITIZE_STRING);
                $profil->email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
                $profil->save();
            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherProfile', ['id' => $profil->id]));
    }

    public function updateProfilePhoto(){
        //On recupere le profil
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        //On recupere la liste assosiciee
        $profil = m\User::where('id', '=', $id)->first();

        //SI L'UTILISATEUR EST CONNECTÉ AU SITE
        if(isset($_SESSION['id'])){
            //PROTECTION PERMISSIONS
            if($profil->id == $_SESSION['id']){

                //FICHIER DE L'IMAGE
                $target_dir = "web/profile/";
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
                    //Verification de l'upload de l'image
                    $target_file = $target_dir . "profile" . $profil->id . "." . $imageFileType;
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $profil->img = "/" . $target_file;
                        $profil->save();
                    }
                }

            }
        }

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('afficherProfile', ['id' => $profil->id]));
    }
}