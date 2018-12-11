<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;

class AllListeView extends AbstractView
{

    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct() {
        $this->l = m\Liste::get();
        $this->viewName = "Toutes les listes";
    }

    public function render(){
        $html = <<<END
        <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Toutes les listes</h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
END;
        foreach ($this->l as $liste){
            $html .= <<<END
            <tr>
                <td class="mailbox-star"><i class="fa fa-star text-yellow"></i></td>
                <td class="mailbox-name"><a href="/index.php/liste/{$liste->no}">{$liste->titre}</a></td>
                <td class="mailbox-subject">{$liste->description}</td>
                <td class="mailbox-subject">Créée par {$liste->user_id}</td>
            </tr>
END;
        }
        $html .= <<<END
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
END;
        echo $html;
    }

}