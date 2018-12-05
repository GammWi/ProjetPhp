<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

class ItemView
{

    private $i;

    /**
     * ListeView constructor.
     */
    public function __construct($item) {
        $this->i = $item;
    }

    public function render(){
        $html = <<<END
<div class="box box-widget widget-user-2">
    <div class="widget-user-header bg-green">
        <div class="widget-user-image">
            <img class="img-circle" src="../img/{$this->i->img}" alt="User Avatar">
        </div>
        <h3 class="widget-user-username">{$this->i->nom} ({$this->i->tarif} €)</h3>
        <h5 class="widget-user-desc">{$this->i->descr}</h5>
    </div>
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li><a href="#">Supprimer</a></li>
        </ul>
    </div>
</div>
END;
        return $html;
    }

}