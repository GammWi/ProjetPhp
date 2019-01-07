<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\controleurs as c;
use wishlist\models as m;

class ItemView extends AbstractView
{
    private $i;

    /**
     * ListeView constructor.
     */
    public function __construct($item) {
        $this->i = $item;
        $this->viewName = "Item = " . $this->i->nom;
    }

    public function render(){
        $app = \Slim\Slim::getInstance();
        $reservation = $app->urlFor('ReserverUnItem');

        $connected = $_SESSION['id'];

        $html = <<<END
<!--<div class="box box-widget widget-user-2" style="float: left; width: 49%; min-height: 150px; margin-right: 1%;">-->
<div class="box box-widget widget-user-2">
    <div class="widget-user-header bg-red">
        <div class="widget-user-image">
            <img class="img-circle" src="{$this->i->img}" alt="User Avatar">
        </div>
        <h3 class="widget-user-username">{$this->i->nom} ({$this->i->tarif} €)</h3>
        <h5 class="widget-user-desc">{$this->i->descr}</h5>
    </div>
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
END;
        if($connected){
            $liste = $this->i->liste;
            $user = m\User::where('id', '=', $_SESSION['id'])->first();
            if ($liste->user_id == $_SESSION['id'] || $user->estParticipant($liste)) {
                if ($this->i->reservation_user == $_SESSION['id']){
                    $html .= <<<END
            <li><a href="/index.php/annulerReservation/{$this->i->id}">Vous avez réservé cet item, votre message : '<i>{$this->i->reservation_message}</i>' </br><b>Annnuler la réservation</b></a></li>
END;
                } else if ( $this->i->reservation_user == null ) {
                    $html .= <<<END
            <li><a data-toggle="modal" data-target="#modal-reserver-item-{$this->i->id}">Réserver cet item</a></li>
END;
                } else {
                    $html .= <<<END
            <li><a>Cet item est déjà réservé par <b>{$this->i->reservationUser->name}</b>.</br>Son message : '<i>{$this->i->reservation_message}</i>'</a></li>
END;
                }
                $html.= <<<END
            <li><a href="/index.php/deleteItem/{$this->i->id}">Supprimer</a></li>
END;
            } else if ($this->i->reservation_user != null){
                $html .= <<<END
            <li><a><i>Cet item est déjà réservé par <b>{$this->i->reservationUser->name}</b></i></a></li>
END;
            }
        } else {
            if($this->i->reservation_user != null){
                $html .= <<<END
            <li><a><i>Cet item est déjà réservé par <b>{$this->i->reservationUser->name}</b></i></a></li>
END;
            }
        }
        $html .= <<<END
        </ul>
    </div>
</div>
 <!-- modal pour la reservation d'un item -->
        <div class="modal fade" id="modal-reserver-item-{$this->i->id}">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Réserver un item : {$this->i->nom}</h4>
              </div>
              <div class="modal-body">
                <form action="$reservation" method="post" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="item_id" value="{$this->i->id}"/>
                        <div class="form-group">
                          <label>Message pour le propriétaire</label>
                          <input type="text" class="form-control" name="reservation_message" placeholder="Message pour le propriétaire" value="{$this->i->reservation_message}">
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Réserver un item</button>
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
        return $html;
    }
}