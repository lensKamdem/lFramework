<?php

?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <span class="glyphicon glyphicon-user"></span>
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li>
            <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/index">
            <i class="fa fa-th"></i> <span>DashBoard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction">
            <i class="fa fa-files-o"></i>
            <span>Articles</span>
            <span class="pull-right-container">
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction"><i class="fa fa-circle-o"></i> All Articles</a></li>
            <li><a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction"><i class="fa fa-circle-o"></i> Categories</a></li>
            <li><a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction"><i class="fa fa-circle-o"></i> Tags</a></li>
          </ul>
        </li>
        <li>
            <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction">
            <i class="fa fa-th"></i> <span>Media</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li>
            <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction">
            <i class="fa fa-th"></i> <span>Pages</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li>
            <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction">
            <i class="fa fa-th"></i> <span>Users</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li>
            <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/dashboard/inConstruction">
            <i class="fa fa-th"></i> <span>Settings</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
