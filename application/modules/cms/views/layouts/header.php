<?php
use LFramework\Helpers\Html as Html;

$session = LFramework::$application->getSession();
$user = $session->get("user");
?>

<header class="main-header">
    <!-- Logo -->
    <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/index" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IAI</b>Cameroon</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IAI</b>Cameroon</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user"></span>
                <span class="hidden-xs">
                    <?= ucfirst($user["firstName"])." ".ucfirst($user["lastName"]); ?>
                </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                  <span class="glyphicon glyphicon-user"></span>
                <p>
                  <?= ucfirst($user["firstName"])." ".ucfirst($user["lastName"])." - ".
                        ucfirst($user["role"]); ?>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="center-block">
                    <a href="<?= \LFramework::$application->getHomeUrl(); ?>/admin/dashboard/logout"
                       class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>


