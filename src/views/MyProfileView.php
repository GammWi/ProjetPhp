<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;

class MyProfileView extends AbstractView
{

    private $u;
    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct($user) {
        $this->u = $user;
        $this->l = m\Liste::where('user_id', '=', $this->u->id)->get();
        $this->viewName = "Profil de " . $this->u->name;
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $update = $app->urlFor('updateProfileInfos');

        $html = <<<END
        <div class="row">
        <div class="col-md-3">

          <div class="box box-danger">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="/dist/img/user2-160x160.jpg" alt="User profile picture">

              <h3 class="profile-username text-center">{$this->u->name}</h3>

              <p class="text-muted text-center">{$this->u->email}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Listes</b> <a class="pull-right">{$this->u->listes->count()}</a>
                </li>
                <li class="list-group-item">
                  <b>Amis</b> <a class="pull-right">0</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active danger"><a href="#timeline" data-toggle="tab">Mes listes</a></li>
              
              <li><a href="#settings" data-toggle="tab">Paramètres</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="timeline">
                <ul class="timeline timeline-inverse">
END;
        foreach ($this->l as $liste){
            $html .= $this->renderListes($liste);
        }
        $html .= <<<END
                      <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                      </li>
                </ul>
              </div>

              <div class="tab-pane" id="settings">
                <form action="$update" method="post" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Pseudonyme</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="pseudonyme" placeholder="Pseudonyme" value="{$this->u->name}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="email" placeholder="Email" value="{$this->u->email}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Statut</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="statut" placeholder="Écrivez un statut..." value="{$this->u->statut}">
                    </div>
                  </div>
                  <input type="hidden" name="id" value="{$this->u->id}"/>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Mettre à jour</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
END;
        echo $html;
    }

    public function renderListes(m\Liste $liste){
        $html = <<<END
<li>
                        <i class="fa fa-list bg-gray"></i>
    
                        <div class="timeline-item">
                          <span class="time">{$liste->items->count('*')} item(s)</span>
    
                          <h3 class="timeline-header no-border"><a href="/index.php/liste/{$liste->no}">{$liste->titre}</a>
                          </h3>
                        </div>
                    </li>
END;
        return $html;
    }

}