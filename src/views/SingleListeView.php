<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;
use wishlist\views as v;

/**
 * Class SingleListeView
 * @package wishlist\views
 */
class SingleListeView extends AbstractView
{

    private $l, $participations;

    /**
     * ListeView constructor.
     */
    public function __construct(m\Liste $liste, $alertMessage = null) {
        $this->l = $liste;
        $this->viewName = $this->l->titre;
        $this->participations = $this->l->participations;
        $this->viewDescription = "créée par " . $this->l->user->name;
        $this->alertMessage = $alertMessage;
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $addItem = $app->urlFor('addItem');
        $addParticipant = $app->urlFor('addParticipant');
        $nouveauTitre = $app->urlFor('renommerLaListe');
        $suppresionListe = $app->urlFor('supprimerListe');

        $html = <<<END
    <div class="row">
        <div class="col-md-4">
        
            <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Items ({$this->l->items()->count()})</h3>
END;
        $estParticipant = false;
        foreach($this->participations as $participation){
            if($participation->user->id == $_SESSION['id']){
                $estParticipant = true;
            }
        }
        if($_SESSION['id'] == $this->l->user_id || $estParticipant) {
            $html .= <<<END
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-add-item">
                        Ajouter un item
                    </button>
                  </div>
END;
        }
        $html .= <<<END
                </div>
              </div>
            
             <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Participants ({$this->participations->count()})</h3>
END;
        if($this->l->user_id == $_SESSION['id']) {
            $html .= <<<END
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-add-participant">
                        Ajouter un participant
                    </button>
                  </div>
END;

        }
        $html .= <<<END
                </div>
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
END;
        foreach($this->participations as $participant){
            $html .= <<<END
                <li>
                  <img src="/web/profile/default.jpg" alt="User Image">
                  <a class="users-list-name" href="/index.php/userListes/{$participant->user->id}">{$participant->user->name}</a>
END;
            if($_SESSION['id'] == $this->l->user_id || $_SESSION['id'] == $participant->user->id){
                $html .= <<<END
                <span class="users-list-date"><a href="/index.php/deleteParticipant/{$this->l->no}/{$participant->user->id}">Supprimer</a></span>
END;
            }
            $html .= <<<END
                </li>
END;
        }
        $html .= <<<END
                  </ul>
                </div>
                <div class="box-footer text-center">
                  <span><i>Les participants peuvent éditer la liste</i></span>
                </div>
              </div>
              
END;
        if($this->l->user_id == $_SESSION['id']) {
            $html .= <<<END
            
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Actions</h3>

                  <div class="box-tools pull-right">
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-rename-liste">Renommer la liste</button>
                      <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-supprimer-liste">Supprimer la liste</button>
                    </div>
                  </div>
                </div>
              </div>
END;
        }
        $html .= <<<END
        </div>
        <div class="col-md-8">
END;

        foreach ($this->l->items as $item){
            $iv = new v\ItemView($item);
            $html .= $iv->render();
        }
        $html .= <<<END
    </div>
</div>

        <div class="modal fade" id="modal-add-item">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ajouter un item</h4>
              </div>
              <div class="modal-body">
                
                <form action="$addItem" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Nom de l'item</label>
                          <input type="text" class="form-control" name="nom" placeholder="Nom">
                        </div>
                        <div class="form-group">
                          <label>Description</label>
                          <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>
                        <div class="form-group">
                          <label>Prix</label>
                          <div class="input-group">
                            <input type="number" class="form-control" name="prix" placeholder="10">
                            <span class="input-group-addon">€</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputFile">Image</label>
                          <input type="file" name="fileToUpload">
                          <p class="help-block">Formats acceptés : PNG, JPG, JPEG</p>
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Ajouter l'item</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-add-participant">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ajouter un participant</h4>
              </div>
              <div class="modal-body">
                <form action="$addParticipant" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Adresse email du participant</label>
                          <input type="email" class="form-control" name="email" placeholder="participant@exemple.com">
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Ajouter le participant</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        
        <!-- modal pour le renommage d'une liste -->
        <div class="modal fade" id="modal-rename-liste">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Renommer la liste</h4>
              </div>
              <div class="modal-body">
                <form action="$nouveauTitre" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Nom de la liste</label>
                          <input type="text" class="form-control" name="titre" placeholder="Nom de la liste" value="{$this->l->titre}">
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                        <div class="form-group">
                          <label>Description de la liste</label>
                          <input type="text" class="form-control" name="description" placeholder="Description de la liste" value="{$this->l->description}">
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Renommer la liste</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <!-- modal pour la suppression d'une liste -->
        <div class="modal fade" id="modal-supprimer-liste">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Supprimer la liste</h4>
              </div>
              <div class="modal-body">
                <form action="$suppresionListe" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="callout callout-danger">
                            <h4>Attention !</h4>
            
                            <p>Vous vous apprétez à supprimer une liste, <b>{$this->l->items()->count()} item(s)</b> sera/seront alors supprimé(s). Voulez vous continuer ?</p>
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Supprimer la liste</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
END;
        echo $html;
    }

}