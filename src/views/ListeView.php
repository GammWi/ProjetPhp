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
<td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
<td class="mailbox-name"><a href="read-mail.html"> {$this->l->titre} </a></td>
<td class="mailbox-subject"> {$this->l->description} </td>
<td class="mailbox-subject"> Créée par {$this->l->user_id} </td>
</td>
END;
        return $html;
    }

}