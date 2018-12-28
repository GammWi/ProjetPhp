<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-28
 * Time: 09:42
 */

namespace wishlist\views;

/**
 * Class MultiplesListesView qui permet d'afficher toutes les listes donnees en parametre
 * @package wishlist\views
 */
class MultiplesListesView extends AbstractView
{

    private $listes = array();

    public function _Construct($l){
        $this->listes = $l;
        $this->viewName = "Vue pour de multiples listes";
    }

    public function render(){
        $html = <<<END
        <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Vue pour de multiples listes</h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
END;
        foreach ($this->listes as $liste){
            $html .= <<<END
            <tr>
                <td class="mailbox-star"><i class="fa fa-star text-yellow"></i></td>
                <td class="mailbox-name"><a href="/index.php/liste/{$liste->no}">{$liste->titre}</a></td>
                <td class="mailbox-subject">{$liste->description}</td>
                <td class="mailbox-subject">Créée par {$liste->user->name}</td>
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