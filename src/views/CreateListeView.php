<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;

class CreateListeView extends AbstractView
{

    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct() {
        $this->viewName = "Création d'une liste";
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $insertNewListe = $app->urlFor('insertNewListe');
        $associerListe = $app->urlFor('associerListe');
        $html = <<<END
        <div class="row">
            <div class="col-md-7">
                <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Nouvelle liste</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <form action="$insertNewListe" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="control-label">Nom de la liste</label>
                                <input type="text" class="form-control" name="titre" placeholder="Nom">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input type="text" class="form-control" placeholder="Description" name="description">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-danger pull-right">Créer</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Associer une liste existante</h3>
                </div>
                <div class="box-body">
                <!-- /.box-header -->
                <!-- form start -->
END;
        if(isset($_SESSION['id'])){
            $html .= <<<END
                <form action="$associerListe" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="token" class="control-label">Token de la liste</label>
                      <input type="text" name="token" class="form-control" id="token" placeholder="XXXXXXXXXXXXX">
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-default pull-right">Associer la liste à mon compte</button>
                  </div>
                  <!-- /.box-footer -->
            </form>
END;
        } else {
            $html .= <<<END
                        <div class="callout callout-danger">
                            <h4>Erreur !</h4>

                            <p>Vous devez être connecté à un compte utilisateur pour associer une liste.</p>
                        </div>
END;
        }
        $html .= <<<END
        </div>
                </div>
            </div>
        </div>

        <!-- modal pour associer une liste -->
        <div class="modal fade" id="modal-associer-liste">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Associer une liste existante à mon compte</h4>
              </div>
              <div class="modal-body">
END;
        if(isset($_SESSION['id'])){
            $html .= <<<END
                <form action="$nouveauMessage" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Token de la liste</label>
                          <input type="text" class="form-control" name="message" placeholder="xxxxxxxxxxxxx">
                        </div>
                        <input type="hidden" name="user_id" value="{$_SESSION['id']}"/>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Associer la liste à mon compte</button>
                    </div>
                </form>
END;
        } else {
            $html .= <<<END
                        <div class="callout callout-danger">
                            <h4>Erreur !</h4>

                            <p>Vous devez être connecté à un compte utilisateur pour associer une liste.</p>
                        </div>
END;
        }
        $html .= <<<END
              </div>
            </div>
          </div>
        </div>
END;
        echo $html;
    }

}