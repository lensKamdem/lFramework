<?php

?>

<div class="login-box">
  <div class="login-logo">
      <a href="<?= LFramework::$application->getHomeUrl(); ?>/admin/home/index"><b>IAI</b>Cameroon</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
      <p class="login-box-msg"><?= LFramework::t("admin", "Sign in to start your session"); ?>
    </p>

    <form action="<?= LFramework::$application->getHomeUrl(); ?>/admin/home/index" method="post">
        <?= $this->errors($errors, "login"); ?>
      <div class="form-group has-feedback">
        <input type="text" name="userName" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <?= $this->errors($errors, "userName"); ?>
      </div>
      <div class="form-group has-feedback">
          <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <?= $this->errors($errors, "password"); ?>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
  
  <div class="login-return">
      <a class="" href="<?= LFramework::$application->getHomeUrl(); ?>"> Back to IAI Cameroon</a>
  </div>
      
  
</div>
<!-- /.login-box -->

