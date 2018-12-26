<?php
    $app = \Slim\Slim::getInstance();
?>
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
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/web/profile/default.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
	<?php
	if(!empty($_SESSION))
	{
	      echo '<p>' . $_SESSION["name"] . '</p>';
	}
	else
	{
               echo '<p> Visiteuré</p>';
	}
	?> 
            <a href="/Deconnexion.php"><i class="fa fa-circle text-success"></i> Se déconnecter</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Navigation</li>
            <li><a href="/index.php/listes"><i class="fa fa-list"></i> <span>Toutes les listes</span></a></li>
            <li><a href="/index.php/listeMembres"><i class="fa fa-users"></i> <span>Membres</span></a></li>
            <li class="header">Espace personnel</li>
            <li><a href="/index.php/afficherMyProfile"><i class="fa fa-user"></i> <span>Mon compte</span></a></li>
            <li><a href="/index.php/createListe"><i class="fa fa-plus"></i> <span>Créer une liste</span></a></li>
            <li><a href=""><i class="fa fa-list"></i> <span>Mes listes</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

