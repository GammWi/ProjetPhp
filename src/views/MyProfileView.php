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
        $html = <<<END
        <div class="row">
        <div class="col-md-3">

          <div class="box box-danger">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">

              <h3 class="profile-username text-center">{$this->u->name}</h3>

              <p class="text-muted text-center">{$this->u->email}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Listes</b> (<a href="/index.php/userListes/{$this->u->id}">voir</a>) <a class="pull-right">{$this->u->listes->count('*')}</a>
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
              
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
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
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
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