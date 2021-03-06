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
        if($this->l->user != null){
            $this->viewDescription = "créée par " . $this->l->user->name;
        } else {
            $this->viewDescription = "créée par un utilisateur anonyme";
        }
        $this->alertMessage = $alertMessage;
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $addItem = $app->urlFor('addItem');
        $addParticipant = $app->urlFor('addParticipant');
        $nouveauTitre = $app->urlFor('renommerLaListe');
        $suppresionListe = $app->urlFor('supprimerListe');
        $rendrePublique = $app->urlFor('rendrePublique');
        $rendrePrive = $app->urlFor('rendrePrivee');
        $nouveauMessage = $app->urlFor('nouveauMessageListe');
        $modifierDestinataire = $app->urlFor('modifierDestinataireListe');
        $modifierEcheance = $app->urlFor('modifierEcheanceListe');

        $connected = isset($_SESSION['id']);
        $html = <<<END
    <div class="row">
        <div class="col-md-4">

            <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Items ({$this->l->items()->count()})</h3>
END;

        if($this->l->estParticipantSession($_SESSION)){
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
        if($this->l->estProprietaireSession($_SESSION)){
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
                  <img class="img-circle" src="{$participant->user->img}" alt="User Image">
                  <a class="users-list-name" href="{$app->urlFor('afficherListesUserId', array('userId' => $participant->user->id))}">{$participant->user->name}</a>
END;
            if($this->l->estProprietaireSession($_SESSION)){
                $html .= <<<END
                <span class="users-list-date"><a href="{$app->urlFor('deleteParticipant', array('lid' => $this->l->no, 'uid' => $participant->user->id))}">Supprimer</a></span>
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
        if($this->l->estParticipantSession($_SESSION)){
            $html .= <<<END
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Informations</h3>

                  <div class="box-body no-padding">
                    <div class="box-body">
                    </div>
                  </div>
                  <div class="box-footer text-center">
END;
            if($this->l->destinataire_id != null){
                if($this->l->estProprietaireSession($_SESSION)){
                    $html .= <<<END
                <span><b>Destinataire :</b> {$this->l->destinataire->name} (<a data-toggle="modal" data-target="#modal-modifier-destinataire">Modifier</a> | <a href="{$app->urlfor('supprimerDestinataireListe', array( 'lid' => $this->l->no))}">Supprimer</a>)</span></br></br>
END;
                } else {
                    $html .= <<<END
                <span><b>Destinataire :</b> {$this->l->destinataire->name}</span></br></br>
END;
                }
            } else {
                if($this->l->estProprietaireSession($_SESSION)){
                    $html .= <<<END
                <span><a data-toggle="modal" data-target="#modal-modifier-destinataire">Définir un destinataire</a></span></br></br>
END;
                } else {
                    $html .= <<<END
                <span><i>Aucun destinataire</i></span></br></br>
END;
                }
            }
            if($this->l->expiration != null){
                if($this->l->estProprietaireSession($_SESSION)){
                    $html .= <<<END
                <span><b>Échéance :</b> {$this->l->expiration} (<a data-toggle="modal" data-target="#modal-modifier-echeance">Modifier</a> | <a href="{$app->urlfor('supprimerEcheanceListe', array( 'lid' => $this->l->no))}">Supprimer</a>)</span>
END;
                } else {
                    $html .= <<<END
                <span><b>Échéance :</b> {$this->l->expiration}</span>
END;
                }
            } else {
                if($this->l->estProprietaireSession($_SESSION)){
                    $html .= <<<END
                <span><a data-toggle="modal" data-target="#modal-modifier-echeance">Définir une date d'échéance</a></span>
END;
                } else {
                    $html .= <<<END
                <span><i>Aucun date d'échéance</i></span>
END;
                }
            }
            $html .= <<<END
                </div>
                </div>
              </div>
END;
        }
        $html .= <<<END

        </div>
        <div class="col-md-8">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active danger"><a href="#items" data-toggle="tab">Items</a></li>
END;
        if($this->l->estParticipantSession($_SESSION)) {
            $html .= <<<END
              <li><a href="#messages" data-toggle="tab">Messages</a></li>
END;
        }
        $html .= <<<END
END;
        if($this->l->estProprietaireSession($_SESSION)) {
            $html .= <<<END
              <li><a href="#settings" data-toggle="tab">Paramètres</a></li>
END;
        }
        $html .= <<<END
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="items">
END;

        foreach ($this->l->items as $item){
            $iv = new v\ItemView($item);
            $html .= $iv->render();
        }
        $html .= <<<END
              </div>
END;
        if($this->l->estParticipantSession($_SESSION)) {
            $html .= <<<END
              <div class="tab-pane" id="messages">
                <button class="btn btn-default btn-block" data-toggle="modal" data-target="#modal-nouveau-message"><b>Nouveau message</b></button>
                </br>
END;
            foreach ($this->l->messages as $message) {
                $mv = new v\MessageView($message);
                $html .= $mv->render();
            }
            $html .= <<<END
              </div>
END;
            if ($this->l->estProprietaireSession($_SESSION)) {
                $html .= <<<END
              <div class="tab-pane" id="settings">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Informations</h4>
END;
                $html .= <<<END
                <p><b>Nom :</b> {$this->l->titre}</p>
END;
                if($this->l->user_id != null){
                    $html .= <<<END
                <p><b>Propriétaire :</b> {$this->l->user->name}</p>
END;
                } else {
                    $html .= <<<END
                <p><b>Propriétaire :</b> Aucun (liste rattachée à une session)</p>
END;
                }
                $html .= <<<END
                <p><b>Token de propriété :</b> {$this->l->token}</p>
                <p><i>Le token peut être utilisé sur un compte utilisateur (sur la page "Nouvelle liste") afin de transférer la propriété de la liste.</i></p>
                    </div>
                    <div class="col-md-4">
                        <h4>Actions</h4>
END;
                if($this->l->publique == 0){
                    $html .= <<<END
                      <button type="button" class="btn btn-success btn-block btn-sm" data-toggle="modal" data-target="#modal-publier-liste">Rendre publique</button>
END;
                } else {
                    $html .= <<<END
                      <button type="button" class="btn btn-danger btn-block btn-sm" data-toggle="modal" data-target="#modal-privatiser-liste">Rendre privée</button>
END;
                }
                $html .= <<<END
                      <button type="button" class="btn btn-default btn-block btn-sm" data-toggle="modal" data-target="#modal-rename-liste">Renommer la liste</button>
                      <button type="button" class="btn btn-default btn-block btn-sm" data-toggle="modal" data-target="#modal-supprimer-liste">Supprimer la liste</button>
                    </div>
                </div>
            </div>
END;
            }
        }
        $html .= <<<END
            </div>
          </div>
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

        <!-- modal pour rendre publique une liste -->
        <div class="modal fade" id="modal-publier-liste">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Publier la liste</h4>
              </div>
              <div class="modal-body">
                <form action="$rendrePublique" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="callout callout-danger">
                            <h4>Attention !</h4>

                            <p>Vous vous apprétez à rendre cette liste publique. Tous les utilisateurs du site pourront la consulter. Voulez vous continuer ?</p>
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Rendre cette liste publique</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- modal pour rendre une liste privée -->
        <div class="modal fade" id="modal-privatiser-liste">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Privatiser la liste</h4>
              </div>
              <div class="modal-body">
                <form action="$rendrePrive" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="callout callout-danger">
                            <h4>Attention !</h4>

                            <p>Vous vous apprétez à rendre cette liste privée. Les utilisateurs du site qui ne font pas partie de la liste des participants ne pourront plus voir la liste. Voulez vous continuer ?</p>
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Rendre cette liste privée</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!-- modal modifier destinataire -->
        <div class="modal fade" id="modal-modifier-destinataire">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modifier le destinataire</h4>
              </div>
              <div class="modal-body">
                <form action="$modifierDestinataire" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Adresse mail du destinataire</label>
END;
                          if($this->l->destinataire != null){
                              $html .= <<<END
<input type="email" class="form-control" name="email" placeholder="Adresse mail du destinataire" value="{$this->l->destinataire->email}">
END;
                          } else {
                              $html .= <<<END
<input type="email" class="form-control" name="email" placeholder="Adresse mail du destinataire">
END;
                          }
            $html .= <<<END
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Modifier le destinataire</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!-- modal modifier destinataire -->
        <div class="modal fade" id="modal-modifier-echeance">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modifier la date d'échéance</h4>
              </div>
              <div class="modal-body">
                <form action="$modifierEcheance" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Date d'échéance (format : année-mois-jour)</label>
                          <input type="date" class="form-control" name="echeance" placeholder="AAAA-MM-JJ" value="{$this->l->expiration}">
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Modifier la date d'échéance</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div>

END;
        if($connected){
            $html .= <<<END
        <!-- modal pour envoyer un nouveau message -->
        <div class="modal fade" id="modal-nouveau-message">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Privatiser la liste</h4>
              </div>
              <div class="modal-body">
                <form action="$nouveauMessage" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label>Message</label>
                          <input type="text" class="form-control" name="message" placeholder="Écrivez votre message ici...">
                        </div>
                        <input type="hidden" name="liste_id" value="{$this->l->no}"/>
                        <input type="hidden" name="user_id" value="{$_SESSION['id']}"/>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Envoyer le message</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
END;
        }

        echo $html;
    }

}
