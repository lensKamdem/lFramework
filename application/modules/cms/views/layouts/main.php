<?php
use LFramework\Helpers\Html as Html;
use Application\AssetBundles\CmsAsset as CmsAsset;

/* @var $this LFramework\Base\View */
/* @var $iaiAsset IaiAsset */
$cmsAsset = new CmsAsset();
CmsAsset::register($this);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= LFramework::$application->_language; ?>">
<head>
  <meta charset="<?= LFramework::$application->_charset; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= Html::encode($this->_title); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <?php $this->head(); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody(); ?>

<div class="wrapper">

  <!-- Main Header -->
  <?= $this->render("header"); ?>
  <!-- End Main Header -->

  <!-- Sidebar -->
  <?= $this->render("sidebar"); ?>
  <!-- End Sidebar -->

  <!-- Content -->
  <?= $content; ?>
  <!-- End Content -->
  
  <!-- Footer -->
  <?= $this->render("footer"); ?>
  <!-- End Footer -->

  <!-- Control Sidebar -->
  
  <!-- End Control Sidebar -->
  
</div>

<?php $this->endBody(); ?>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
</body>
</html>
<?php $this->endPage(); ?>
