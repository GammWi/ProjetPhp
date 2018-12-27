<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\controleurs as c;

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

        $liste = $this->i->liste;
        if ($liste->user_id == $_SESSION['id'] || c\ControleurListe::estParticipant($liste, $_SESSION['id'])) {
            if ($this->i->reservation_user == $_SESSION['id']){
                $html .= <<<END
            <li><a href="/index.php/annulerReservation/{$this->i->id}">Annnuler la réservation</a></li>
END;
            } else if ( $this->i->reservation_user == null ) {
                $html .= <<<END
            <li><a href="/reserverItem/{$this->i->id}">Réserver cet item</a></li>
END;
            } else {
                $html .= <<<END
            <li><a><i>Cet item est déjà réservé par <b>{$this->i->reservationUser->name}</b></i></a></li>
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
        $html .= <<<END
        </ul>
    </div>
</div>
END;
        return $html;
    }

}