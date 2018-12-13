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

class SingleListeView extends AbstractView
{

    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct(m\Liste $liste) {
        $this->l = $liste;
        $this->viewName = "Liste : " . $this->l->titre;
    }

    public function render(){
        $html = "";
        foreach ($this->l->items as $item){
            $iv = new v\ItemView($item);
            $iv->render();
        }
        $html .= <<<END
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-default">
                    Ajouter un item
                </button>
END;
        echo $html;
    }

}