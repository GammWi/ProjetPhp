<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\controleurs as c;

class MessageView extends AbstractView
{

    private $m,$u;

    /**
     * ListeView constructor.
     */
    public function __construct($message) {
        $this->m = $message;
        $this->u = $this->m->user;
        $this->viewName = "Message = " . $this->m->id;
    }

    public function render(){
        $html = <<<END
        <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="{$this->u->img}" alt="user image">
                        <span class="username">
                          <a>{$this->u->name}</a>
                        </span>
END;
        if($this->m->user_id == $_SESSION['id']){
            $html .= <<<END
<span class="description">{$this->m->created_at} - <a href="/index.php/supprimerMessage/{$this->m->id}">Supprimer</a></span>
END;
        } else {
            $html .= <<<END
<span class="description">{$this->m->created_at}</span>
END;

        }
        $html .= <<<END
                  </div>
                  <!-- /.user-block -->
                  <p>{$this->m->message}</p>
                </div>
END;
        return $html;
    }

}