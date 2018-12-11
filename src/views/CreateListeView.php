<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 05/12/2018
 * Time: 14:05
 */

namespace wishlist\views;

use wishlist\models as m;

class CreateListeView extends AbstractView
{

    private $l;

    /**
     * ListeView constructor.
     */
    public function __construct() {
        $this->viewName = "Création d'une liste";
    }

    public function render(){
        $html = <<<END
        <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Nouvelle liste</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="/insertNewListe" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="titre" id="titre" class="col-sm-2 control-label">Nom de la liste</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="titre" placeholder="Nom">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Description" name="description">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-danger pull-right">Créer</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
END;
        echo $html;
    }

}