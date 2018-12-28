<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-27
 * Time: 14:33
 */

namespace wishlist\views;
use wishlist\models as m;

class ErreurView extends AbstractView
{

    protected $message;

    /**
     * ListeView constructor.
     */
    public function __construct($m) {
        $this->viewName = "ERREUR";
        $this->message = $m;
    }

    public function render(){
        $html = <<<END
            <p>{$this->message}</p>
            <a type="button" class="btn btn-danger btn-sm" href="/index.php">
                Retourner à l'accueil
            </a>
END;
        echo $html;
    }
}