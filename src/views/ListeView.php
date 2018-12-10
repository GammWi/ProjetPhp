<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

class ListeView
{

    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct($liste) {
        $this->l = $liste;
    }

    public function render(){
        $html = <<<END
<tr>
<td class="mailbox-star"><i class="fa fa-star text-yellow"></i></td>
<td class="mailbox-name"><a href="liste.php?liste={$this->l->no}">{$this->l->titre}</a></td>
<td class="mailbox-subject">{$this->l->description}</td>
<td class="mailbox-subject">Créée par {$this->l->user_id}</td>
</tr>
END;
        return $html;
    }

}