<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;

class MembresListeView extends AbstractView
{

    private $u,$l,$i;

    /**
     * ListeView constructor.
     */
    public function __construct() {
        $this->u = m\User::get();
        $this->l = m\Liste::get();
        $this->i = m\Item::get();
        $this->viewName = "Membres du site";
    }

    public function render(){
        $html = <<<END
        <div class="row">
        <div class="col-md-9">
        <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Liste des membres</h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
END;
        foreach ($this->u as $user){
            $html .= <<<END
            <tr>
                <td class="mailbox-star"><i class="fa fa-user"></i></td>
                <td class="mailbox-name"><a href="/index.php/userListes/{$user->id}">{$user->name}</a></td>
                <td class="mailbox-subject">{$user->email}</td>
            </tr>
END;
        }
        $html .= <<<END
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-red">
            <div class="inner">
              <h3>{$this->u->count()}</h3>
              <p>Membres inscrits</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
          </div>
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{$this->l->count()}</h3>
              <p>Listes créées</p>
            </div>
            <div class="icon">
              <i class="fa fa-list"></i>
            </div>
          </div>
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{$this->i->count()}</h3>
              <p>Items ajoutés</p>
            </div>
            <div class="icon">
              <i class="fa fa-tag"></i>
            </div>
          </div>
    </div>
</div>
END;
        echo $html;
    }

}