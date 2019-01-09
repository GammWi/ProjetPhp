<?php
/**
 * Created by PhpStorm.
 * User: fannypierre
 * Date: 2018-12-07
 * Time: 17:30
 */

namespace wishlist\views;

use wishlist\models as m;
use wishlist\views as v;

abstract class AbstractView
{

    protected $viewName, $viewDescription, $alertMessage;

    public abstract function render();

    public function renderHeader(){
        $html = <<<END
        <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MyWishList | {$this->viewName}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/ressources/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/ressources/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/ressources/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/ressources/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/ressources/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/ressources/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/ressources/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/ressources/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/ressources/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/ressources/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
END;
        $html .= "</head>";
        echo $html;
    }

    /**
     * fonction qui permet d'afficher le corps
     */
    function renderBody(){
        $app = \Slim\Slim::getInstance();

        $connected = isset($_SESSION['id']);
        if($connected){
            $user = m\User::where('id', '=', $_SESSION['id'])->first();
        }
        $html = <<<END
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">

<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>M</b>WL</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>My</b>WishList</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
END;
        if(!$connected){
            $html .= <<<END
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/web/defaultProfile.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Visiteur</p>
            <a href="/login.php"><i class="fa fa-circle text-success"></i> Se connecter</a>
            </div>
        </div>
END;
        } else {
            $html .= <<<END
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{$user->img}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{$user->name}</p>
            <a href="/Deconnexion.php"><i class="fa fa-circle text-danger"></i> Se déconnecter</a>
            </div>
        </div>
END;
        }
        $html .= <<<END
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Navigation</li>
            <li><a href="{$app->urlFor('afficherToutesLesListes')}"><i class="fa fa-list"></i> <span>Toutes les listes</span></a></li>
            <li><a href="{$app->urlFor('afficherMembres')}"><i class="fa fa-users"></i> <span>Membres</span></a></li>
            <li class="header">Espace personnel</li>
END;
        if($connected){
            $html .= <<<END
            <li><a href="{$app->urlFor('afficherMyProfile')}"><i class="fa fa-user"></i> <span>Mon compte</span></a></li>
END;
        }
        $html .= <<<END
            <li><a href="{$app->urlFor('createListe')}"><i class="fa fa-plus"></i> <span>Créer une liste</span></a></li>
            <li><a href="{$app->urlFor('afficherListesUserActuel')}"><i class="fa fa-list"></i> <span>Mes listes</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {$this->viewName}
END;
        if($this->viewDescription != null){
            $html .= <<<END
<small>{$this->viewDescription}</small>
END;
        }
        $html .= <<<END
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!--CONTENU-->

END;
        if($this->alertMessage != null){
            $html .= <<<END
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Attention !</h4>
                {$this->alertMessage}
              </div>
END;
        }
        echo $html;
        $this->render();
        $html = <<<END
            <!--FIN DE CONTENU-->

        </section>
        <!-- /.content -->
    </div>
    
    <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights reserved | IUT Nancy-Charlemagne
</footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/ressources/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/ressources/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="/ressources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="/ressources/bower_components/raphael/raphael.min.js"></script>
<script src="/ressources/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="/ressources/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/ressources/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/ressources/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/ressources/real/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/ressources/bower_components/moment/min/moment.min.js"></script>
<script src="/ressources/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/ressources/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/ressources/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="/ressources/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/ressources/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/ressources/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/ressources/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/ressources/dist/js/demo.js"></script>
</body>
END;
    echo $html;
    }

    /**
     * fonction permettant d'afficher tout le contenu d'une page
     * @return string
     */
    public function renderFinal()
    {
        $html = <<<END
<!DOCTYPE html>
<html>
END;
        echo $html;
        $this->renderHeader();
        $this->renderbody() ;
        $html = "</html>";
        echo $html;
    }
}
