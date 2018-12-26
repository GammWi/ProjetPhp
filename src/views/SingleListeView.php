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
        $app = \Slim\Slim::getInstance();
        $addItem = $app->urlFor('addItem');

        $html = "";
        foreach ($this->l->items as $item){
            $iv = new v\ItemView($item);
            $iv->render();
        }
        $html .= <<<END
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-add-item">
                    Ajouter un item
                </button>
                
<div class="modal fade" id="modal-add-item">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ajouter un item</h4>
              </div>
              <div class="modal-body">
                
                <form action="$addItem" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Nom de l'item</label>
                          <input type="text" class="form-control" name="nom" placeholder="Nom">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Description</label>
                          <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Image</label>
                          <input type="text" class="form-control" name="image" placeholder="Exemple : image.jpg">
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Ajouter l'item</button>
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
        echo $html;
    }

}